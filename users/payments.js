const urlParams = new URLSearchParams(window.location.search);
document.addEventListener("DOMContentLoaded", async function() {
  
  // Verifique se existe um ID para determinar se é uma edição ou criação
  const id = urlParams.get('id');
  
  if (id) {
    let cpf = $('input[name="cpf"]').val(urlParams.get('cpf'));
    const responsePayment = await getPayments(cpf.val());
    paymentsTable(responsePayment);

    // Preencher o formulário com os dados recebidos
    $('input[name="id"]').val("new"); 
    $('input[name="nome"]').val(urlParams.get('nome'));
       
  } else {
    // Configurações para criação de novo sócio
  }
});

const getPayments = async (cpf) => {
  try {
    const response = await fetch("./queries/sql_get_pagamentos.php", {
      method: "POST",
      body: JSON.stringify({ cpf: cpf })
    });

    const dados = await response.json();

    // Verifique a resposta
    if (dados && dados.status && dados.data) {
      return dados.data; // Retorna a chave 'data' que contém os pagamentos
    } 
   
  } catch (error) {
    console.error('Erro ao enviar os dados:', error);
    Swal.fire('Erro!', 'Ocorreu um erro ao carregar os dados. Tente novamente.', 'error');
    return null; // Retorna null em caso de erro
  }
};


const paymentsTable = (payments) => {
  let htmlBody = "";

  if (!payments || !Array.isArray(payments) || payments.length === 0) {
    htmlBody = `<tr><td colspan="6" class="text-center">Nenhum pagamento encontrado.</td></tr>`;
    $("#tbody").html(htmlBody);
    return; // Retorna caso os pagamentos estejam vazios ou nulos
  }

  payments.forEach(element => {
    htmlBody += `<tr>`;
    htmlBody += `<td>${element.nome}</td>`;
    htmlBody += `<td>${element.cpf}</td>`;
    htmlBody += `<td>${element.mes}</td>`;
    htmlBody += `<td>${element.ano}</td>`;
    htmlBody += `<td>${element.valor}</td>`;
    htmlBody += `<td>${element.formatted_date}</td>`;
    htmlBody += `<td>`;
    htmlBody += `<ul>`;
    htmlBody += `<a href="#" class="mx-1 edit-link" data-id="${element.id}" data-nome="${element.nome}" data-cpf="${element.cpf}" data-mes="${element.mes}" data-ano="${element.ano}" data-valor="${element.valor}"><i class="fas fa-edit"></i></a>`;
    htmlBody += `<a href="#" class="delete-payments" data-id="${element.id}"><i class='fas fa-trash'></i></a>`;
    htmlBody += `</ul>`;
    htmlBody += `</td>`;
    htmlBody += `</tr>`;
  });

  $("#tbody").html(htmlBody);

  $("#table-payments").DataTable();

  // Adicione o evento de clique ao botão de edição
  $('.edit-link').on('click', function(event) {
    event.preventDefault();
    const element = $(this).data();
    $('#id').val(element.id);
    $('#nome').val(element.nome);
    $('#cpf').val(element.cpf);
    $('#mes').val(element.mes);
    $('#ano').val(element.ano);
    $('#valor').val(element.valor);
    // Exiba a div_payments
    $('#div_payments').show();
  });
};

var notyf = new Notyf({
  position: {
    x: 'right',
    y: 'top',
  },
});

// Evento de exclusão do usuário
$(document).on('click', '.delete-payments', async function(event) {
  event.preventDefault();

  const userId = $(this).data('id');
  Swal.fire({
    title: 'Você tem certeza?',
    text: "Deseja excluir esse lançamento?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, excluir!',
    cancelButtonText: 'Cancelar'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const response = await fetch(`./queries/sql_delete_payments.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id: userId })
        });
        const result = await response.json();
        console.log(result);
        if (result.status === 'success') {
          await notyf.success('Lançamento excluído com sucesso!');
          // Remover a linha da tabela
          $(this).closest('tr').remove();
        } else {
          notyf.error('Erro ao excluir o lançamento.');
        }
      } catch (error) {
        console.error('Erro:', error);
        notyf.error('Ocorreu um erro ao excluir o lançamento. Tente novamente.');
      }
    }
  });
});

const cadPagamentoForm = document.getElementById("pagamentos-form");
cadPagamentoForm.addEventListener("submit", function(e){
  e.preventDefault();
  const dadosForm = new FormData(cadPagamentoForm);
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
        const response = await fetch("./queries/sql_set_pagamentos.php", {
          method: "POST",
          body: dadosForm,
        });

        if (!response.ok) {
          throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const dados = await response;

        if (dados.status) {
          await notyf.success('Dados salvos com sucesso!');
          clearFields()
          $('#div_payments').hide();

          const cpf = $('input[name="cpf"]').val();
          const responsePayment = await getPayments(cpf);
          paymentsTable(responsePayment);
          
        } else {
          await notyf.error(dados.msg);
        }
      } catch (error) {
        await notyf.error('Ocorreu um erro ao enviar os dados. Tente novamente.');
      }
    }
  });
});

document.getElementById('btn_payments').addEventListener('click', function() {
  let divPayment = document.getElementById('div_payments');
  if (divPayment.style.display === 'none' || divPayment.style.display === '') {
    divPayment.style.display = 'block';
  } else {
    divPayment.style.display = 'none';
  }
  clearFields();
});

const clearFields = () => {
  $('#mes').val('');
  $('#ano').val('');
  $('#valor').val('');
};