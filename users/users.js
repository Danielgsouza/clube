let element = null;
document.addEventListener("DOMContentLoaded", async function() {
  let users = await getUsers();
  let htmlBody = "";

  users.forEach(element => {

    var colorBadge = element.status == "1" ? "badge-success" : "badge-danger";
    var text = element.status == "1" ? "Ativo"  : "Inativo"
    htmlBody += "<tr>"
    htmlBody += `<td><span class='badge rounded-pill ${colorBadge}'>${text}</td>`;
    htmlBody += `<td>${element.cpf}</td>`;
    htmlBody += `<td>${element.nome}</td>`;
    htmlBody += `<td>${element.usuario}</td>`;
    htmlBody += `<td>${element.email}</td>`;
    htmlBody += `<td>${element.tipo}</td>`;
    htmlBody += `<td>${element.createdAt}</td>`;
    htmlBody += `<td>${element.modifiedAt}</td>`;
    htmlBody += `<td>`;
    htmlBody += `<ul>`;

    htmlBody += `<a href="../users/new-user.php?id=${encodeURIComponent(element.id)}&nome=${encodeURIComponent(element.nome)}&cpf=${encodeURIComponent(element.cpf)}&user=${encodeURIComponent(element.usuario)}&userType=${encodeURIComponent(element.tipo)}&createdAt=${encodeURIComponent(element.createdAt)}&email=${encodeURIComponent(element.email)}&status=${encodeURIComponent(element.status)}" class="mx-1 edit-link"><i class="fas fa-edit"></i></a>`;
    htmlBody += `<a href="#" class="delete-users" data-id="${element.id}"><i class='fas fa-trash'></i></a>`;
    htmlBody += `</ul>`;
    htmlBody += `</td>`;
    htmlBody += `</tr>`;
 });
 $("#tbody").html(htmlBody);

 $("#table-users").DataTable();

});

var notyf = new Notyf({
  position: {
    x: 'right',
    y: 'top',
  },
});

// Evento de exclusão do usuário
$(document).on('click', '.delete-users', async function(event) {
  event.preventDefault();

  const userId = $(this).data('id');
  Swal.fire({
    title: 'Você tem certeza?',
    text: "Deseja excluir esse usuário?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, excluir!',
    cancelButtonText: 'Cancelar'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const response = await fetch(`./queries/sql_delete_users.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ id: userId })
        });
        const result = await response.json();
        console.log(result);
        if (result.status === 'success') {
          await notyf.success('Usuário excluído com sucesso!');
          // Remover a linha da tabela
          $(this).closest('tr').remove();
        } else {
          notyf.error('Erro ao excluir o usuário.');
        }
      } catch (error) {
        console.error('Erro:', error);
        notyf.error('Ocorreu um erro ao excluir o usuário. Tente novamente.');
      }
    }
  });
});


const getUsers = async () => {
  try {
    const response = await fetch("./queries/sql_get_users.php", {
      method: "GET",
    })
    return await response.json();
  } catch (error) {
    console.error('Erro ao enviar os dados:', error);
    Swal.fire('Erro!', 'Ocorreu um erro ao carregar os dados. Tente novamente.', 'error');
  }
}
