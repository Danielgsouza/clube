document.addEventListener("DOMContentLoaded", function() {
  const urlParams = new URLSearchParams(window.location.search);
  
  // Verifique se existe um ID para determinar se é uma edição ou criação
  const id = urlParams.get('id');

  if (id) {
    let cpf = $('input[name="cpf"]').val(urlParams.get('cpf'));
    // Preencher o formulário com os dados recebidos
    $('input[name="id"]').val(urlParams.get('id')); 
    $('input[name="nome"]').val(urlParams.get('nome'));
    $('select[name="mes"]').val(urlParams.get('mes'));
    $('input[name="ano"]').val(urlParams.get('ano'));
    $('input[name="valor"]').val(urlParams.get('valor'));
    $('select[name="tipo"]').val(urlParams.get('tipo'));
       
  } else {
    // Configurações para criação de novo sócio
    $('input[name="id"]').val("new"); 
  }
})

const paymentsForm = document.getElementById("payments-form");
var notyf = new Notyf({
  position: {
    x: 'right',
    y: 'top',
  },
});

paymentsForm.addEventListener("submit", function(e){
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

        const dados = await response;

        if (dados.status) {
          await notyf.success('Dados salvos com sucesso!');
          setTimeout(() => {
            window.location.href = "././payments.php";
          }, 2400);
        } else {
          notyf.error('Erro!', dados.msg);
        }
      } catch (error) {
        notyf.error('Erro!', 'Ocorreu um erro ao enviar os dados. Tente novamente.', 'error');
      }
    }
  });
});
