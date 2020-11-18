export default () => {
    const formElements = document.querySelectorAll('form.js-submit');
    if (formElements && formElements.length > 0) {
        Object.keys(formElements).map(key => formElements[key]).forEach(formElement => {
            const submitHandler = (e) => {
                Object.values(formElement.elements).forEach(element => {
                    if (element.value === '') {
                        element.setAttribute('disabled', true);
                    }
                })
                formElement.submit();
            };
            
            const selectElements = formElement.querySelectorAll('select');
            if (Object.keys(selectElements).length <= 0) {
                return;
            }
            
            Object.keys(selectElements).map(key => selectElements[key]).forEach(selectElement => {
                selectElement.addEventListener('change', submitHandler);
            });
            
        });
    }
};
  