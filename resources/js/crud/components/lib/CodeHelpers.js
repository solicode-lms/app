// CodeHelpers.js
// Utilitaires communs : caret/selection, insertion, classes language-xxx, nettoyage Prism et wrappers CodeJar.

export default class CodeHelpers {
  // Map des alias → clés Prism réelles
  static prismKey(lang) {
    if (!lang) return 'javascript';
    const map = { html: 'markup', js: 'javascript' };
    return map[lang] || lang;
  }

  static getSelection() {
    return window.getSelection ? window.getSelection() : document.getSelection();
  }

  static getCodeAncestorFromSelection() {
    const sel = this.getSelection();
    if (!sel || sel.rangeCount === 0) return null;
    let node = sel.anchorNode;
    if (!node) return null;
    if (node.nodeType === 3) node = node.parentNode;
    return node && node.closest ? node.closest('pre code') : null;
  }

  static insertTextAtCursor(text) {
    const sel = this.getSelection(); if (!sel || sel.rangeCount === 0) return;
    const range = sel.getRangeAt(0);
    range.deleteContents();
    const textNode = document.createTextNode(text);
    range.insertNode(textNode);
    range.setStartAfter(textNode); range.setEndAfter(textNode);
    sel.removeAllRanges(); sel.addRange(range);
  }

  static insertNodeAtCursor(node) {
    const sel = this.getSelection();
    if (!sel || sel.rangeCount === 0) {
      const editable = document.querySelector('.note-editable');
      if (editable) editable.appendChild(node);
      return;
    }
    const range = sel.getRangeAt(0);
    range.collapse(true); range.insertNode(node);
  }

  static placeCaretAtEnd(el) {
    const range = document.createRange(); const sel = this.getSelection();
    range.selectNodeContents(el); range.collapse(false);
    sel.removeAllRanges(); sel.addRange(range);
  }

  static placeCaretAtStart(el) {
    const range = document.createRange(); const sel = this.getSelection();
    range.selectNodeContents(el); range.collapse(true);
    sel.removeAllRanges(); sel.addRange(range);
  }

  static insertPlainTextIntoContentEditable(el, text) {
    const sel = this.getSelection(); if (!sel || sel.rangeCount === 0) return;
    const range = sel.getRangeAt(0); if (!el.contains(range.startContainer)) return;
    range.deleteContents(); range.insertNode(document.createTextNode(text));
    range.collapse(false); sel.removeAllRanges(); sel.addRange(range);
  }

  static setCodeLanguage(codeEl, newLang) {
    [...codeEl.classList].forEach(c => { if (/^language-/.test(c)) codeEl.classList.remove(c); });
    codeEl.classList.add(`language-${newLang}`);
  }

  static getCodeLanguage(codeEl) {
    const cl = [...codeEl.classList].find(c => /^language-/.test(c));
    return cl ? cl.replace(/^language-/, '') : null;
  }

  static cleanCodeElement(codeEl) {
    const lang = this.getCodeLanguage(codeEl);
    const text = codeEl.textContent;
    codeEl.innerHTML = '';
    codeEl.textContent = text;
    if (lang) this.setCodeLanguage(codeEl, lang);
  }

  /**
   * Nettoie tout le HTML d’un éditeur Summernote :
   * - convertit les wrappers CodeJar restants en <pre><code>
   * - supprime tout markup Prism dans les <code>
   */
  static cleanPrismFromHtml(html) {
    const container = document.createElement('div');
    container.innerHTML = html;

    // Convertir d’éventuels wrappers CodeJar résiduels
    container.querySelectorAll('.sn-codejar-wrapper').forEach(wrapper => {
      const ed = wrapper.querySelector('.sn-codejar-editor');
      const lang = wrapper.dataset.lang || 'javascript';
      const pre = document.createElement('pre');
      const code = document.createElement('code');
      code.className = `language-${lang}`;
      code.textContent = ed ? ed.textContent : '';
      pre.appendChild(code);
      wrapper.parentNode.replaceChild(pre, wrapper);
    });

    // Nettoyer chaque <pre><code> (supprimer <span class="token ...">)
    container.querySelectorAll('pre code').forEach(code => this.cleanCodeElement(code));
    return container.innerHTML;
  }
}
