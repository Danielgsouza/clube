let element = null;
document.addEventListener("DOMContentLoaded", async function() {
  let users = await getUsers();
  let htmlBody = "";

  users.forEach(element => {

    console.log(element.tipo)
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
    htmlBody += `</ul>`;
    htmlBody += `</td>`;
    htmlBody += `</tr>`;
 });
 $("#tbody").html(htmlBody);

 $("#table-users").DataTable();

  // Função para abrir o modal e preencher os dados
  // const openModal = (element) => {
  //   $('#modal-nome').text(element.nome);
  //   $('#modal-cpf').text(element.cpf);

  //   const imagePath = `../users/uploads/${element.cpf}.jpg`;
  //   $('#foto-preview').attr('src', imagePath).on('error', function() {
  //     $(this).attr('src', '../assets/images/avatar/icon.png');
  //   });

  //   const qrCodePath = `../users/qrcode/${element.cpf}.png`;
  //   $('#modal-qrCode').attr('src', qrCodePath);

  // };

  // $(document).on('click', '.view-carteirinha', function(event) {
  //   event.preventDefault();
  //   element = $(this).data();
  //   openModal(element);
  // });

  // Adicione o evento de clique ao botão de download da imagem dentro do modal
  // document.getElementById('download-image').addEventListener('click', function() {
  //   const cardContainer = document.getElementById('card-container');
  //   if (!cardContainer) {
  //     console.error('Elemento card-container não encontrado.');
  //     return;
  //   }

  //   html2canvas(cardContainer).then(canvas => {
  //     const imgData = canvas.toDataURL('image/png');
  //     const link = document.createElement('a');
  //     link.href = imgData;
  //     link.download = `[${element.cpf}] ${element.nome} - carteirinha.png`;
  //     document.body.appendChild(link);
  //     link.click();
  //     document.body.removeChild(link);
  //   }).catch(error => {
  //     console.error('Erro ao capturar o elemento com html2canvas:', error);
  //   });
  // });

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
