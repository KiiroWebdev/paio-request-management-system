import { initNavigation } from './navigation';
import { initServices } from './services';
import { initRequest } from './request';
import { initOrganization } from './organization';
document.addEventListener('DOMContentLoaded', () => {

    const wizard = {

        currentStep: 0,

        pages: document.querySelectorAll('.wizard-page'),

        steps: document.querySelectorAll('.wizard-step'),

        progress: document.getElementById('wizardProgress'),

        nextBtn: document.getElementById('nextBtn'),

        prevBtn: document.getElementById('prevBtn'),

        beforeNext: null,

        onUpdate: null,

        update: null,

    };

    initNavigation(wizard);
    initServices(wizard);
    initRequest(wizard);
    initOrganization(wizard);

    wizard.update();

});