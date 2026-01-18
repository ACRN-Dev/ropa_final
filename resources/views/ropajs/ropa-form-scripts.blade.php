<script>
document.addEventListener("DOMContentLoaded", function() {

    // -----------------------------
    // Generic "Other" fields toggle
    // -----------------------------
    function handleOtherFields(name) {
        const container = document.getElementById(name + '_other_container');
        const addBtn = document.getElementById('add_' + name + '_other');
        if(!container) return;

        const elements = document.querySelectorAll(`[name^="${name}"]`);

        function toggleOther() {
            let show = false;
            elements.forEach(el => {
                if ((el.tagName === 'SELECT' && el.value === 'Other') ||
                    (el.type === 'checkbox' && el.checked && el.value === 'Other')) {
                    show = true;
                }
            });
            container.classList.toggle('hidden', !show);
            if(addBtn) addBtn.classList.toggle('hidden', !show);
        }

        elements.forEach(el => el.addEventListener('change', toggleOther));
        toggleOther();

        if(addBtn){
            addBtn.addEventListener('click', () => {
                const input = document.createElement('input');
                input.type = 'text';
                input.name = name + '_other[]';
                input.placeholder = 'Specify other';
                input.className = 'w-full border rounded-lg p-2 mt-2';
                container.appendChild(input);
            });
        }
    }

    ['organisation','department','data_sources','data_formats','personal_data_categories','technical_measures','organisational_measures','lawful_basis'].forEach(handleOtherFields);

    // -----------------------------
    // Organisation / Department selects
    // -----------------------------
    ['organisation_name','department'].forEach(id => {
        const select = document.getElementById(id);
        const wrapper = document.getElementById('other_' + id + '_wrapper');
        if(!select || !wrapper) return;
        const toggleWrapper = () => wrapper.classList.toggle('hidden', select.value !== 'Other');
        select.addEventListener('change', toggleWrapper);
        toggleWrapper(); // initial
    });

    // -----------------------------
    // Access Control toggle
    // -----------------------------
    const accessSelect = document.getElementById('access_control_select');
    const accessSection = document.getElementById('access_security_measures_section');
    if(accessSelect && accessSection){
        const toggleAccess = () => {
            if(accessSelect.value==='1'){
                accessSection.classList.remove('hidden');
            } else {
                accessSection.classList.add('hidden');
                accessSection.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked=false);
                accessSection.querySelectorAll('input[type="text"]').forEach(inp => inp.value='');
            }
        };
        accessSelect.addEventListener('change', toggleAccess);
        toggleAccess();
    }

    // -----------------------------
    // Information Sharing toggle
    // -----------------------------
    const infoShared = document.getElementById('information_shared');
    const sharingDetails = document.getElementById('sharing_details');
    const localContainer = document.getElementById('local_sharing_container');
    const transborderContainer = document.getElementById('transborder_sharing_container');
    const sharingCheckboxes = document.querySelectorAll('input[name="sharing_type[]"]');

    const toggleSharing = () => {
        if(!infoShared) return;
        if(infoShared.value==='1'){
            sharingDetails.classList.remove('hidden');
            updateSharingTypeVisibility();
        } else {
            sharingDetails.classList.add('hidden');
            localContainer.classList.add('hidden');
            transborderContainer.classList.add('hidden');
            sharingCheckboxes.forEach(cb => cb.checked=false);
            document.querySelectorAll('#local_fields_container input, #transborder_fields_container input').forEach(i => i.value='');
        }
    };

    const updateSharingTypeVisibility = () => {
        const selectedValues = Array.from(sharingCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        localContainer.classList.toggle('hidden', !selectedValues.includes('local'));
        transborderContainer.classList.toggle('hidden', !selectedValues.includes('transborder'));
    };

    if(infoShared){
        infoShared.addEventListener('change', toggleSharing);
        toggleSharing(); // initial
    }

    sharingCheckboxes.forEach(cb => cb.addEventListener('change', updateSharingTypeVisibility));

    // -----------------------------
    // Add dynamic input fields
    // -----------------------------
    function addField(btnId, containerId, inputName, placeholder){
        const btn = document.getElementById(btnId);
        const container = document.getElementById(containerId);
        if(!btn || !container) return;
        btn.addEventListener('click', ()=> {
            const input = document.createElement('input');
            input.type='text';
            input.name=inputName;
            input.placeholder=placeholder;
            input.className='w-full border rounded-lg p-2 mt-2';
            container.appendChild(input);
        });
    }

    addField('add_local_field','local_fields_container','local_organizations[]','Enter local organisation');
    addField('add_transborder_field','transborder_fields_container','transborder_countries[]','Enter country');
    addField('add_other_organisation','other_organisation_container','other_organisation_name[]','Enter organisation name');
    addField('add_other_department','other_department_container','other_department[]','Enter department name');

    // -----------------------------
    // Handle "I do not know" checkbox behavior
    // When "I do not know" is selected, uncheck all other options in the group
    // When any other option is selected, uncheck "I do not know"
    // -----------------------------
    const checkboxGroups = [
        'data_sources[]',
        'data_formats[]',
        'personal_data_categories[]',
        'technical_measures[]',
        'organisational_measures[]',
        'lawful_basis[]'
    ];

    checkboxGroups.forEach(groupName => {
        const checkboxes = document.querySelectorAll(`input[name="${groupName}"]`);
        const idkCheckbox = Array.from(checkboxes).find(cb => cb.value === 'I do not know');
        
        if (!idkCheckbox) return;

        // When "I do not know" is checked, uncheck all others
        idkCheckbox.addEventListener('change', function() {
            if (this.checked) {
                checkboxes.forEach(cb => {
                    if (cb !== idkCheckbox) {
                        cb.checked = false;
                    }
                });
            }
        });

        // When any other checkbox is checked, uncheck "I do not know"
        checkboxes.forEach(cb => {
            if (cb !== idkCheckbox) {
                cb.addEventListener('change', function() {
                    if (this.checked) {
                        idkCheckbox.checked = false;
                    }
                });
            }
        });
    });

    // -----------------------------
    // Remove empty fields on submit
    // -----------------------------
    const form = document.querySelector('form');
    if(form){
        form.addEventListener('submit', () => {
            document.querySelectorAll('input[type="text"]').forEach(input => {
                if(input.value.trim() === '') input.remove();
            });
        });
    }

});
</script>
