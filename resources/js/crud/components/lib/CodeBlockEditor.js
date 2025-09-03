// CodeBlockEditor.js
// Gestion d’un bloc <pre><code> en mode édition CodeJar + Prism (ouverture, rehighlight, sortie, clic hors, paste texte brut)

import { CodeJar } from 'codejar';
import Prism from 'prismjs';
import 'prismjs/themes/prism.css';

// Langages nécessaires
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-markup';   // HTML = "markup"
import 'prismjs/components/prism-css';
import 'prismjs/components/prism-php';
import 'prismjs/components/prism-bash';
// Dépendances utiles
import 'prismjs/components/prism-clike';
import 'prismjs/components/prism-markup-templating';

import CodeHelpers from './CodeHelpers.js';

export default class CodeBlockEditor {
  static CLASS_WRAPPER = 'sn-codejar-wrapper';
  static CLASS_EDITOR  = 'sn-codejar-editor';

  constructor(options = {}) {
    this.options = {
      codeJarTab: '  ',
      onFinish: null,   // callback(preEl, addParagraphAfter)
      ...options
    };
    this.jar = null;
    this.wrapper = null;
    this.editorEl = null;
    this.lang = 'javascript';
    this._clickOutsideHandler = null;
    this._rehighlight = () => {};
  }

  isActive() {
    return !!this.jar && !!this.wrapper;
  }

  getLanguage() {
    return this.lang;
  }

  setLanguage(newLang) {
    this.lang = newLang || 'javascript';
    this._rehighlight();
  }

  getText() {
    return this.editorEl ? this.editorEl.textContent : '';
  }

  startOnCodeEl(codeEl) {
    if (this.isActive()) return;

    // Nettoyer Prism dans ce bloc
    CodeHelpers.cleanCodeElement(codeEl);
    const pre = codeEl.closest('pre');

    // Wrapper + éditeur contenteditable
    const wrapper = document.createElement('div');
    wrapper.className = CodeBlockEditor.CLASS_WRAPPER;
    wrapper.style.position = 'relative';

    const editor = document.createElement('div');
    editor.className = CodeBlockEditor.CLASS_EDITOR;
    editor.contentEditable = 'true';
    Object.assign(editor.style, {
      whiteSpace: 'pre',
      fontFamily: 'monospace',
      fontSize: '0.95em',
      border: '3px solid #ddd',
      borderRadius: '4px',
      padding: '8px',
      background: '#f8f8f8'
    });
    editor.textContent = codeEl.textContent;

    pre.parentNode.replaceChild(wrapper, pre);
    wrapper.appendChild(editor);

    // Langue active
    this.lang = CodeHelpers.getCodeLanguage(codeEl) || 'javascript';
    wrapper.dataset.lang = this.lang;

    // Highlight (appelé par CodeJar après chaque modif)
    const rehighlight = (ed) => {
      const code = ed.textContent;
      const prismKey = CodeHelpers.prismKey(this.lang);
      const grammar = Prism.languages[prismKey];
      if (!grammar) return;
      ed.innerHTML = Prism.highlight(code, grammar, prismKey);
    };
    this._rehighlight = () => rehighlight(editor);

    // Instancier CodeJar (pas besoin de new) + premier highlight
    this.jar = CodeJar(editor, rehighlight, { tab: this.options.codeJarTab });
    rehighlight(editor);

    // Bloquer Summernote pendant l’édition (mais pas input !)
    const stopEv = (ev) => ev.stopPropagation();
    editor.addEventListener('keydown', stopEv, true);
    editor.addEventListener('keypress', stopEv, true);
    editor.addEventListener('keyup', stopEv, true);
    // ⚠️ ne pas stopper 'input', sinon CodeJar ne déclenche pas son rehighlight
    editor.addEventListener('drop', (e) => { e.preventDefault(); }, true);

    // Fallback : forcer un rehighlight manuel sur chaque input
    editor.addEventListener('input', () => {
      requestAnimationFrame(() => rehighlight(editor));
    });

    // Coller en texte brut
    editor.addEventListener('paste', (e) => {
      e.preventDefault();
      let text = (e.clipboardData || window.clipboardData)?.getData('text/plain') || '';
      text = text.replace(/\r\n?/g, '\n');
      CodeHelpers.insertPlainTextIntoContentEditable(editor, text);
    });

    // Raccourcis de sortie
    editor.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' && e.ctrlKey) { e.preventDefault(); this.finish(true); }
      else if (e.key === 'Escape')       { e.preventDefault(); this.finish(false); }
    });

    // Click hors du wrapper => sortie
    const snRoot = wrapper.closest('.note-editor') || document;
    const clickOutsideToExit = (ev) => {
      if (!this.isActive()) return;
      const target = ev.target;
      if ((snRoot && snRoot.contains(target)) && !wrapper.contains(target)) {
        this.finish(false);
      }
    };
    document.addEventListener('mousedown', clickOutsideToExit, true);
    this._clickOutsideHandler = clickOutsideToExit;

    // Mémoriser refs et focus
    this.wrapper = wrapper;
    this.editorEl = editor;
    CodeHelpers.placeCaretAtEnd(editor);
  }

  finish(addParagraphAfter = false) {
    if (!this.isActive()) return;
    const text = this.getText();
    const lang = this.lang || this.wrapper.dataset.lang || 'javascript';

    // Détruire CodeJar
    this.jar.destroy?.();
    this.jar = null;

    // Retirer le handler "click dehors"
    if (this._clickOutsideHandler) {
      document.removeEventListener('mousedown', this._clickOutsideHandler, true);
      this._clickOutsideHandler = null;
    }

    // Reconstruire un <pre><code> propre
    const pre = document.createElement('pre');
    const code = document.createElement('code');
    code.className = `language-${lang}`;
    code.textContent = text;
    pre.appendChild(code);

    // Remplacer le wrapper par <pre>
    const parent = this.wrapper.parentNode;
    parent.replaceChild(pre, this.wrapper);

    // RAZ refs
    this.wrapper = null;
    this.editorEl = null;

    // Focus après
    if (addParagraphAfter) {
      const p = document.createElement('p'); p.innerHTML = '<br>';
      pre.parentNode.insertBefore(p, pre.nextSibling);
      CodeHelpers.placeCaretAtStart(p);
    } else {
      CodeHelpers.placeCaretAtEnd(code);
    }

    // Callback externe éventuel
    if (typeof this.options.onFinish === 'function') {
      this.options.onFinish(pre, addParagraphAfter);
    }
  }
}
