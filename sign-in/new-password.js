document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('new-password-form');
  const password = document.getElementById('password');
  const confirmPassword = document.getElementById('confirm-password');

  form.addEventListener('submit', function(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    if (password.value !== confirmPassword.value) {
      Swal.fire('As senhas não coincidem!',  'Por favor, tente novamente.', 'error');
      return;
    }

    const formData = {
      token: form.querySelector('input[name="token"]').value,
      password: password.value
    };

    fetch('./queries/sql_new_password.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
      Swal.fire('Sucesso!', data.message, 'success', {
        allowOutsideClick: false
      }).then(async (result) => {
        if (result.isConfirmed) {
          // Redirecionar para outra tela
          window.location.href = '../index.php';
        }
      });
    })
    .catch(error => {
      Swal.fire('Erro!', error.message, 'error')
    });
  });
});