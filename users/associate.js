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
      // htmlBody += `<td>${element.estado_civil}</td>`;
      htmlBody += `<td>${element.profissao}</td>`;
      htmlBody += `<td>${dataNascimento}</td>`;
      htmlBody += `<td>${element.telefone}</td>`;
      htmlBody += `<td>${element.endereco}</td>`;
      // htmlBody += `<td>${element.email}</td>`;
      htmlBody += `<td>`;
      htmlBody += `<ul>`;

      htmlBody += `<a href="new-associate.php?id=${element.id}&nome=${encodeURIComponent(element.nome)}&cpf=${element.cpf}&titulo=${encodeURIComponent(element.titulo)}&estado_civil=${encodeURIComponent(element.estado_civil)}&profissao=${encodeURIComponent(element.profissao)}&data_nascimento=${encodeURIComponent(element.data_nascimento)}&email=${encodeURIComponent(element.email)}&telefone=${element.telefone}&status=${element.status}&endereco=${element.endereco}" class="edit-link"><i class="fas fa-edit"></i></a>`;
      htmlBody += `<a href="payments.php?id=${element.id}&nome=${encodeURIComponent(element.nome)}&cpf=${element.cpf}" class="mx-1"><i class='fas fa-dollar-sign'></i></a>`;
      
      // Exemplo de como adicionar o link para abrir o modal no seu HTML
      htmlBody += `<a href="#" class=" view-carteirinha" data-toggle="modal" data-target="#carteirinhaModal" data-id="${element.id}" data-nome="${encodeURIComponent(element.nome)}" data-cpf="${element.cpf}" data-estado_civil="${encodeURIComponent(element.estado_civil)}" data-profissao="${encodeURIComponent(element.profissao)}" data-data_nascimento="${encodeURIComponent(element.data_nascimento)}" data-email="${encodeURIComponent(element.email)}" data-telefone="${element.telefone}" data-status="${element.status}" data-titulo="${element.titulo}" data-tipo=""><i class="fa-solid fa-address-card"></i></a>`;
      htmlBody += `<a href="#" class="download-qrcode mx-1" data-cpf="${element.cpf}"><i class='fas fa-qrcode'></i></a>`; 
      htmlBody += `<a href="#" class="delete-associate" data-id="${element.id}"><i class='fas fa-trash'></i></a>`;
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
    $('#modal-titulo').text(decodeURIComponent(element.titulo));

    // Carregar a imagem do dependente
    const imagePathBase = `../users/uploads/${element.cpf}`;
    const imageExtensions = ['.png', '.jpg', '.jpeg']; // Extensões possíveis
    const imgElement = document.getElementById('foto-preview');

    loadImage(imagePathBase, imageExtensions, imgElement);

    // Carregar o QR Code
    const qrCodePath = `../users/qrcode/${element.cpf}.png`;
    $('#modal-qrCode-front').attr('src', qrCodePath);
    $('#modal-qrCode-back').attr('src', qrCodePath);
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

  // Evento de download do QR code
  $(document).on('click', '.download-qrcode', function(event) {
    event.preventDefault();
    const cpf = $(this).data('cpf');
    const qrCodePath = `../users/qrcode/${cpf}.png`;
    const link = document.createElement('a');
    link.href = qrCodePath;
    link.download = `${cpf}-qrcode.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });

  var notyf = new Notyf({
    position: {
      x: 'right',
      y: 'top',
    },
  });

  // Evento de exclusão do usuário
  $(document).on('click', '.delete-associate', async function(event) {
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
          const response = await fetch(`./queries/sql_delete_associate.php`, {
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

  const btnFisico = document.getElementById('btn-fisico');
  const btnDigital = document.getElementById('btn-digital');
  const cardContainer = document.getElementById('card-container');
  const cardFront = document.getElementById('card-front');
  const cardBack = document.getElementById('card-back');
  const qrCodeFront = document.getElementById('qrCodeFront');
  const qrCodeBack = document.getElementById('qrCodeBack');

  // Função para mostrar a versão digital
  function mostrarDigital() {
    // cardFront.classList.add('d-none');
    cardBack.classList.add('d-none');
  
    // Exibir QR Code na frente
    qrCodeFront.classList.remove('d-none');
    qrCodeBack.classList.add('d-none');
  }

  // Função para mostrar a versão física
  function mostrarFisico() {
    cardBack.classList.remove('d-none');

    // Exibir QR Code no verso
    qrCodeFront.classList.add('d-none');
    qrCodeBack.classList.remove('d-none');
  }

  // Definindo ações para os botões
  btnFisico.addEventListener('click', function() {
    mostrarFisico();
  });

  btnDigital.addEventListener('click', function() {
    mostrarDigital();
  });

  // Inicializa com a versão física visível
  mostrarFisico();
  
});
