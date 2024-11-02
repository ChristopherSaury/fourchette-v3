document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("contact-form");
    const loadingSpinner = document.getElementById('loading-spinner');
    const responseContainer = document.getElementById("response");

    form.addEventListener("submit", function(event) {
        event.preventDefault();

        resetError();

        if(form['ct-name'].value === ''){
            return formError('name-error');
        }else if(form['ct-email'].value === ''){
            return formError('email-error');
        }else if(form['ct-subject'].value === ''){
            return formError('subject-error');
        }else if(form['ct-message'].value === ''){
            return formError('message-error');
        }

        loadingSpinner.style.display = 'block';

        const formData = new FormData(form);
        const xhr = new XMLHttpRequest(); 

        xhr.open("POST", "/contact/email"); 
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    form.reset();   
                    loadingSpinner.style.display = 'none';
                    responseContainer.style.display = 'grid';
                } else {
                    responseContainer.innerHTML = "Erreur interne.";
                }
            }
        };

        xhr.send(formData); // Send the form data
    });
});


function formError(input){
    return document.getElementById(input).style.display = 'initial';
}
function resetError(){
    document.getElementById('name-error').style.display = 'none';
    document.getElementById('email-error').style.display = 'none';
    document.getElementById('subject-error').style.display = 'none';
    document.getElementById('message-error').style.display = 'none';
}
