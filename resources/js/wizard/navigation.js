export function initNavigation(wizard) {

    const { pages, steps, progress, nextBtn, prevBtn } = wizard;

    function update() {

        pages.forEach((page, index) => {

            page.classList.toggle('active', index === wizard.currentStep);

        });

        steps.forEach((step, index) => {

            step.classList.remove('active', 'completed');

            if (index < wizard.currentStep) {

                step.classList.add('completed');

            } else if (index === wizard.currentStep) {

                step.classList.add('active');

            }

        });

        progress.style.width =
            ((wizard.currentStep + 1) / pages.length) * 100 + '%';

        prevBtn.disabled = wizard.currentStep === 0;

        if (wizard.currentStep === pages.length - 1) {

            nextBtn.type = 'submit';

            nextBtn.innerHTML = `
                Submit
                <i class="bi bi-check-circle ms-1"></i>
            `;

        } else {

            nextBtn.type = 'button';

            nextBtn.innerHTML = `
                Next
                <i class="bi bi-arrow-right ms-1"></i>
            `;

        }

        if (wizard.onUpdate) {

            wizard.onUpdate();

        }

    }

    nextBtn.addEventListener('click', () => {

       if (!nextBtn || !prevBtn) {
    return;
}

        if (wizard.currentStep < pages.length - 1) {

            wizard.currentStep++;

            update();

        }

    });

    prevBtn.addEventListener('click', () => {

        if (wizard.currentStep > 0) {

            wizard.currentStep--;

            update();

        }

    });

    wizard.update = update;
}