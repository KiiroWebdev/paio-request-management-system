export function initServices(wizard) {

    const search = document.getElementById('serviceSearch');

    const selectedBox = document.getElementById('selectedServices');

    const serviceInputs =
        document.querySelectorAll('input[name="services[]"]');

    function refresh() {

        const checked =
            document.querySelectorAll(
                'input[name="services[]"]:checked'
            );

        selectedBox.innerHTML = '';

        if (!checked.length) {

            selectedBox.innerHTML =
                '<div class="text-muted">No service selected.</div>';

            return;

        }

        checked.forEach(service => {

            selectedBox.innerHTML += `
                <div class="badge bg-primary text-start w-100 mb-2">
                    <i class="bi bi-check-circle me-2"></i>
                    ${service.dataset.name}
                </div>
            `;

        });

    }

    function validate() {

        if (wizard.currentStep !== 0) {

            wizard.nextBtn.disabled = false;

            return true;

        }

        const ok =
            document.querySelectorAll(
                'input[name="services[]"]:checked'
            ).length > 0;

        wizard.nextBtn.disabled = !ok;

        return ok;

    }

    serviceInputs.forEach(input => {

        input.addEventListener('change', () => {

            refresh();

            validate();

        });

    });

    search.addEventListener('keyup', function () {

        const keyword = this.value.toLowerCase();

        document.querySelectorAll('.service-item')
            .forEach(card => {

                card.style.display =
                    card.innerText
                        .toLowerCase()
                        .includes(keyword)
                        ? ''
                        : 'none';

            });

    });

    wizard.beforeNext = validate;

    wizard.onUpdate = validate;

    refresh();

    validate();
}