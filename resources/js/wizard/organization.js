export function initOrganization(wizard){

    const organization =
        document.getElementById('organizationSelect');

    const preview =
        document.getElementById('organizationPreview');

    function validate(){

        if(wizard.currentStep !== 2){

            return true;

        }

        const ok = organization.value !== '';

        wizard.nextBtn.disabled = !ok;

        return ok;

    }

    organization.addEventListener('change',function(){

        const text =
            this.options[this.selectedIndex].text;

        if(this.value){

            preview.classList.remove('d-none');

            preview.innerHTML = `
                <strong>Selected Organization</strong><br>
                ${text}
            `;

        }else{

            preview.classList.add('d-none');

        }

        validate();

    });

    const oldBeforeNext = wizard.beforeNext;

    wizard.beforeNext = ()=>{

        if(wizard.currentStep===2){

            return validate();

        }

        return oldBeforeNext ? oldBeforeNext() : true;

    };

    const oldUpdate = wizard.onUpdate;

    wizard.onUpdate = ()=>{

        if(oldUpdate){

            oldUpdate();

        }

        validate();

    };

}