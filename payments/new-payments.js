document.addEventListener("DOMContentLoaded", function() {
  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get('id');

  if (id) {
    $('input[name="id"]').val(urlParams.get('id')); 
    $('input[name="nome"]').val(urlParams.get('nome'));
    $('select[name="mes"]').val(urlParams.get('mes'));
    $('input[name="ano"]').val(urlParams.get('ano'));
    $('input[name="valor"]').val(urlParams.get('valor'));
    $('select[name="tipo"]').val(urlParams.get('tipo'));
  } else {
    $('input[name="id"]').val("new"); 
  }
});

const paymentsForm = document.getElementById("payments-form");
var notyf = new Notyf({
  position: {
    x: 'right',
    y: 'top',
  },
});

paymentsForm.addEventListener("submit", function(e) {
  e.preventDefault();
  const dadosForm = new FormData(paymentsForm);
  Swal.fire({
    title: 'Você tem certeza?',
    text: "Deseja salvar este Pagamento?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, salvar!',
    cancelButtonText: 'Cancelar'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const response = await fetch("./queries/sql_set_payments.php", {
          method: "POST",
          body: dadosForm,
        });

        if (!response.ok) {
          throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const dados = await response.json();

        if (dados.status) {
          var button = document.getElementById("btn-submit");
          button.classList.add("disabled");
          await notyf.success('Dados salvos com sucesso!');
          paymentsForm.reset();
          setTimeout(() => {
            window.location.href = "./payments.php";
          }, 1500);
        } else {
          notyf.error('Erro!', dados.msg);
        }
      } catch (error) {
        notyf.error('Erro!', 'Ocorreu um erro ao enviar os dados. Tente novamente.');
      }
    }
  });
});