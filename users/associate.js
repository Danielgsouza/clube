let element = null;
document.addEventListener("DOMContentLoaded", async function() {
  let socios = await getSQL("./queries/sql_get_socios.php")
  let htmlBody = "";
  if (Array.isArray(socios)) {
    socios.forEach(element => {
      const dataParts = element.data_nascimento.split('-');
      const dataNascimento = `${dataParts[2]}/${dataParts[1]}/${dataParts[0]}`;

      var colorBadge = element.status == "1" ? "badge-success" : "badge-danger";
      var text = element.status == "1" ? "Ativo"  : "Inativo"
      htmlBody += "<tr>"
      htmlBody += `<td><span class='badge rounded-pill ${colorBadge}'>${text}</td>`;
      htmlBody += `<td>${element.cpf}</td>`;
      htmlBody += `<td>${element.nome}</td>`;
      htmlBody += `<td>${element.estado_civil}</td>`;
      htmlBody += `<td>${element.profissao}</td>`;
      htmlBody += `<td>${dataNascimento}</td>`;
      htmlBody += `<td>${element.telefone}</td>`;
      htmlBody += `<td>${element.endereco}</td>`;
      htmlBody += `<td>${element.email}</td>`;
      htmlBody += `<td>`;
      htmlBody += `<ul>`;

      htmlBody += `<a href="new-associate.php?id=${element.id}&nome=${encodeURIComponent(element.nome)}&cpf=${element.cpf}&titulo=${encodeURIComponent(element.titulo)}&estado_civil=${encodeURIComponent(element.estado_civil)}&profissao=${encodeURIComponent(element.profissao)}&data_nascimento=${encodeURIComponent(element.data_nascimento)}&email=${encodeURIComponent(element.email)}&telefone=${element.telefone}&status=${element.status}&endereco=${element.endereco}" class="edit-link"><i class="fas fa-edit"></i></a>`;
      // htmlBody += `<a href="dependents.php?id=${element.id}&titular_id=${element.cpf}" class="mx-1"><i class="fas fa-users"></i></a>`;
      htmlBody += `<a href="payments.php?id=${element.id}&nome=${encodeURIComponent(element.nome)}&cpf=${element.cpf}" class="mx-1"><i class='fas fa-dollar-sign'></i></a>`;
      
      // Exemplo de como adicionar o link para abrir o modal no seu HTML
      htmlBody += `<a href="#" class=" view-carteirinha" data-toggle="modal" data-target="#carteirinhaModal" data-id="${element.id}" data-nome="${encodeURIComponent(element.nome)}" data-cpf="${element.cpf}" data-estado_civil="${encodeURIComponent(element.estado_civil)}" data-profissao="${encodeURIComponent(element.profissao)}" data-data_nascimento="${encodeURIComponent(element.data_nascimento)}" data-email="${encodeURIComponent(element.email)}" data-telefone="${element.telefone}" data-status="${element.status}" data-endereco="${element.endereco}" data-tipo=""><i class="fa-solid fa-address-card"></i></a>`;
      htmlBody += `</ul>`;
      htmlBody += `</td>`;
      htmlBody += `</tr>`;
    });
  }
  $("#tbody").html(htmlBody);
  $("#table-socios").DataTable();

  // Função para abrir o modal e preencher os dados
  const openModal = (element) => {
    $('#modal-nome').text(decodeURIComponent(element.nome));
    $('#modal-cpf').text(decodeURIComponent(element.cpf));

    // Carregar a imagem do dependente
    const imagePathBase = `../users/uploads/${element.cpf}`;
    const imageExtensions = ['.png', '.jpg', '.jpeg']; // Extensões possíveis
    const imgElement = document.getElementById('foto-preview');

    loadImage(imagePathBase, imageExtensions, imgElement);

    // Carregar o QR Code
    const qrCodePath = `../users/qrcode/${element.cpf}.png`;
    $('#modal-qrCode').attr('src', qrCodePath);
  };

  $(document).on('click', '.view-carteirinha', function(event) {
    event.preventDefault();
    element = $(this).data();
    openModal(element);
  });

  // Evento de download da carteirinha
  document.getElementById('download-image').addEventListener('click', function() {
    downloadImage(element, 'card-container');
  });

});
