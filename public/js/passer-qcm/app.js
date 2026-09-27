import Alpine from 'https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/module.esm.js';
import registerQcmStore from './stores/qcmStore.js';
import registerQuestionComponent from './components/questionComponent.js';
import registerTimerComponent from './components/timerComponent.js';
import registerSidebarComponent from './components/sidebarComponent.js';
import registerZoneCentraleComponent from './components/zoneCentraleComponent.js';

window.Alpine = Alpine;

registerQcmStore(Alpine);
registerQuestionComponent(Alpine);
registerTimerComponent(Alpine);
registerSidebarComponent(Alpine);
registerZoneCentraleComponent(Alpine);

Alpine.start();
