let element = null;
document.addEventListener("DOMContentLoaded", async function() {
  let dependents = await getSQL("./queries/sql_get_dependents.php");
  let htmlBody = "";
  
  if (Array.isArray(dependents)) {
    dependents.forEach(element => {
      const dataParts = element.data_nascimento.split('-');
      const dataNascimento = `${dataParts[2]}/${dataParts[1]}/${dataParts[0]}`;

      var colorBadge = element.status == "1" ? "badge-success" : "badge-danger";
      var text = element.status == "1" ? "Ativo" : "Inativo";
      
      htmlBody += "<tr>";
      htmlBody += `<td><span class='badge rounded-pill ${colorBadge}'>${text}</td>`;
      htmlBody += `<td>${element.titular_id}</td>`;
      htmlBody += `<td>${element.nome}</td>`;
      htmlBody += `<td>${element.cpf}</td>`;
      htmlBody += `<td>${element.titulo}</td>`;
      htmlBody += `<td>${dataNascimento}</td>`;
      htmlBody += `<td>${element.telefone}</td>`;
      htmlBody += `<td>${element.created_at}</td>`;
      htmlBody += `<td>`;
      htmlBody += `<ul>`;

      htmlBody += `<a href="new-dependents.php?id=${element.id}&nome=${encodeURIComponent(element.nome)}&cpf=${element.cpf}&titulo=${encodeURIComponent(element.titulo)}&titular_id=${encodeURIComponent(element.titular_id)}&data_nascimento=${encodeURIComponent(element.data_nascimento)}&telefone=${element.telefone}&status=${element.status}" class="mx-1 edit-link"><i class="fas fa-edit"></i></a>`;
      
      htmlBody += `<a href="#" class="mx-1 view-carteirinha" data-toggle="modal" data-target="#carteirinhaModal" data-id="${element.id}" data-nome="${encodeURIComponent(element.nome)}" data-cpf="${element.cpf}" data-data_nascimento="${encodeURIComponent(element.data_nascimento)}" data-telefone="${element.telefone}" data-status="${element.status}" data-tipo=""><i class="fa-solid fa-address-card"></i></a>`;
      htmlBody += `</ul>`;
      htmlBody += `</td>`;
      htmlBody += `</tr>`;
    });
  }

  $("#tbody").html(htmlBody);
  $("#table-dependents").DataTable();

});

// Função para abrir o modal e preencher os dados
  const openModal = (element) => {
    $('#modal-nome').text(decodeURIComponent(element.nome));
    $('#modal-cpf').text(decodeURIComponent(element.cpf));

    // Carregar a imagem do dependente
    const imagePathBase = `../dependents/uploads/${element.cpf}`;
    const imageExtensions = ['.png', '.jpg', '.jpeg']; // Extensões possíveis
    const imgElement = document.getElementById('foto-preview');

    loadImage(imagePathBase, imageExtensions, imgElement);

    // Carregar o QR Code
    const qrCodePath = `../dependents/qrcode/${element.cpf}.png`;
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

