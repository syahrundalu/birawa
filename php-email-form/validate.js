document.addEventListener("DOMContentLoaded", function() {
  "use strict";

  let forms = document.querySelectorAll('.php-email-form');

  forms.forEach(function(e) {
    e.addEventListener('submit', function(event) {
      event.preventDefault();

      let thisForm = this;
      let action = thisForm.getAttribute('action');

      if (!action) {
        displayError(thisForm, 'The form action property is not set!');
        return;
      }

      thisForm.querySelector('.loading').classList.add('d-block');
      thisForm.querySelector('.error-message').classList.remove('d-block');
      thisForm.querySelector('.error-message').innerHTML = '';
      thisForm.querySelector('.sent-message').classList.remove('d-block');

      let formData = new FormData(thisForm);

      php_email_form_submit(thisForm, action, formData);
    });
  });

  function php_email_form_submit(thisForm, action, formData) {
    fetch(action, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
      thisForm.querySelector('.loading').classList.remove('d-block');
      if (data.status === 'success') {
        thisForm.querySelector('.sent-message').classList.add('d-block');
        Swal.fire({
          title: 'Success!',
          text: 'Terimakasih sudah menghubungi kami.',
          icon: 'success',
          confirmButtonText: 'OK'
        });
        thisForm.reset();
      } else {
        throw new Error(data.message);
      }
    })
    .catch((error) => {
      displayError(thisForm, error.message);
    });
  }

  function displayError(thisForm, error) {
    thisForm.querySelector('.loading').classList.remove('d-block');
    thisForm.querySelector('.error-message').innerHTML = error;
    Swal.fire({
      title: 'Error!',
      text: error,
      icon: 'error',
      confirmButtonText: 'OK'
    });
    thisForm.querySelector('.error-message').classList.add('d-block');
  }
});
