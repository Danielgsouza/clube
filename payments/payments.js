let payments;
let inputOption = document.getElementById("inputOption");
let outputOption = document.getElementById("outputOption");
let navTabs = document.querySelector('.nav-tabs');

document.addEventListener("DOMContentLoaded", async function() {
  await loadPaymentsData();

  if (inputOption.classList.contains("active")) {
    inputOption.click();
  } 
  
  if(outputOption.classList.contains("active")){
    outputOption.click();
  }
});

async function loadPaymentsData() {
  payments = await getSQL("./queries/sql_get_payments.php");
}

inputOption.addEventListener("click", async function() {
  let htmlBody = "";
  navTabs.classList.remove('nav-danger');
  navTabs.classList.add('nav-success');
  payments.data.filter((payment) => payment.tipo == "E").map((item) => {
    htmlBody += `<tr>`;
    htmlBody += `<td>${item.id}</td>`;
    htmlBody += `<td>${item.nome_pagamento}</td>`;
    htmlBody += `<td>${item.mes}</td>`;
    htmlBody += `<td>${item.ano}</td>`;
    htmlBody += `<td>${item.valor}</td>`;
    htmlBody += `<td>${item.formatted_date}</td>`;
    htmlBody += `<td>`;
    htmlBody += `<ul>`;
    htmlBody += `<a href="./new-payments.php?id=${item.id}&nome=${item.nome_pagamento}&mes=${item.mes}&ano=${item.ano}&valor=${item.valor}&tipo=${item.tipo}" class="mx-1 edit-link" ><i class="fas fa-edit"></i></a>`;
    htmlBody += `<a href="#" class="delete-payments" data-id="${item.id}"><i class='fas fa-trash'></i></a>`;
    htmlBody += `</ul>`;
    htmlBody += `</td>`;
    htmlBody += `</tr>`;
  });

  $("#tbodyInput").html(htmlBody);
  $("#table-input").DataTable();
});

outputOption.addEventListener("click", async function() {
  navTabs.classList.remove('nav-success');
  navTabs.classList.add('nav-danger');

  let htmlBody = "";
  payments.data.filter((payment) => payment.tipo == "S").map((item) => {
    htmlBody += `<tr>`;
    htmlBody += `<td>${item.id}</td>`;
    htmlBody += `<td>${item.nome_pagamento}</td>`;
    htmlBody += `<td>${item.mes}</td>`;
    htmlBody += `<td>${item.ano}</td>`;
    htmlBody += `<td>${item.valor}</td>`;
    htmlBody += `<td>${item.formatted_date}</td>`;
    htmlBody += `<td>`;
    htmlBody += `<ul>`;
    htmlBody += `<a href="./new-payments.php?id=${item.id}&nome=${item.nome_pagamento}&mes=${item.mes}&ano=${item.ano}&valor=${item.valor}&tipo=${item.tipo}" class="mx-1 edit-link" ><i class="fas fa-edit"></i></a>`;
    htmlBody += `<a href="#" class="delete-payments" data-id="${item.id}"><i class='fas fa-trash'></i></a>`;
    htmlBody += `</ul>`;
    htmlBody += `</td>`;
    htmlBody += `</tr>`;
  });

  $("#tbodyOutput").html(htmlBody);
  $("#table-output").DataTable();
});


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