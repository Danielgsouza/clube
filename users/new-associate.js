// let element = null;

document.addEventListener("DOMContentLoaded", async function() {
  await loadSociosData();

  const urlParams = new URLSearchParams(window.location.search);
  const id = urlParams.get('id');

  if (id) {
    // Preencher o formulário com os dados recebidos
    const cpf = urlParams.get('cpf');
    const nome = urlParams.get('nome');
    const data_nascimento = urlParams.get('data_nascimento');
    const estado_civil = urlParams.get('estado_civil');
    const profissao = urlParams.get('profissao');
    const email = urlParams.get('email');
    const telefone = urlParams.get('telefone');
    const status = urlParams.get('status');
    const endereco = urlParams.get('endereco');

    $('input[name="id"]').val(id);
    $('input[name="nome"]').val(nome);
    $('input[name="cpf"]').val(cpf);
    $('input[name="data_nascimento"]').val(data_nascimento);
    $('input[name="estado_civil"]').val(estado_civil);
    $('input[name="titulo"]').val(urlParams.get('titulo'));
    $('input[name="profissao"]').val(profissao);
    $('input[name="email"]').val(email);
    $('input[name="telefone"]').val(telefone);
    $('select[name="status"]').val(status);
    $('input[name="endereco"]').val(endereco);

    // Tentativa de carregar a imagem com diferentes extensões
    const imagePathBase = `../users/uploads/${cpf}`;
    const imageExtensions = ['.png', '.jpg', '.jpeg'];
    let imagePath = null;

    function checkImage(extension) {
      return new Promise((resolve, reject) => {
        const img = new Image();
        img.src = `${imagePathBase}${extension}`;
        img.onload = () => resolve(`${imagePathBase}${extension}`);
        img.onerror = () => reject();
      });
    }

    (async function loadImage() {
      try {
        for (let ext of imageExtensions) {
          try {
            imagePath = await checkImage(ext);
            $('#foto-preview').attr('src', imagePath);
            return;
          } catch (error) {
            continue;
          }
        }
        $('#foto-preview').attr('src', '../assets/images/avatar/icon.png');
      } catch (error) {
        $('#foto-preview').attr('src', '../assets/images/avatar/icon.png');
      }
    })();
  } else {
    $('input[name="id"]').val("new");
    $('input[name="nome"]').val("");
    $('input[name="cpf"]').val("");
    $('input[name="data_nascimento"]').val("");
    $('input[name="estado_civil"]').val("");
    $('input[name="profissao"]').val("");
    $('input[name="email"]').val("");
    $('input[name="telefone"]').val("");
    $('select[name="status"]').val("");
    $('input[name="endereco"]').val("");
  }
});

document.getElementById('upload-button').addEventListener('click', function() {
  document.getElementById('foto').click();
});

function previewImage(event) {
  var reader = new FileReader();
  reader.onload = function(){
    var output = document.getElementById('foto-preview');
    output.src = reader.result;
  };
  reader.readAsDataURL(event.target.files[0]);
}


const cadUsuarioForm = document.getElementById("socio-form");

var notyf = new Notyf({
  position: {
    x: 'right',
    y: 'top',
  },
});

cadUsuarioForm.addEventListener("submit", function(e) {
  e.preventDefault();
  const dadosForm = new FormData(cadUsuarioForm);

  Swal.fire({
    title: 'Você tem certeza?',
    text: "Deseja salvar este sócio?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, salvar!',
    cancelButtonText: 'Cancelar'
  }).then(async (result) => {
    if (result.isConfirmed) {
      try {
        const response = await fetch("./queries/sql_set_socios.php", {
          method: "POST",
          body: dadosForm,
        });

        if (!response.ok) {
          throw new Error(`Erro na requisição: ${response.statusText}`);
        }

        const dados = await response.json(); // Certifique-se de que o servidor está retornando JSON

        console.log(dados);
        if (dados.status) {
          var button = document.getElementById("btn-submit");
          button.classList.add("disabled");
          await notyf.success('Dados salvos com sucesso!');
          setTimeout(() => {
            window.location.href = "./associate.php";
          }, 2400);

        } else {
          console.log(error)
          notyf.error('Erro!', dados.msg);
        }
      } catch (error) {
        notyf.error('Erro!', 'Ocorreu um erro ao enviar os dados. Tente novamente.');
      }
    }
  });
});

async function loadSociosData() {
  let socios = await getSQL("./queries/sql_get_socios.php");
  let htmlBody = "";
  if (Array.isArray(socios)) {
    socios.forEach(element => {
      const dataParts = element.data_nascimento.split('-');
      const dataNascimento = `${dataParts[2]}/${dataParts[1]}/${dataParts[0]}`;

      var colorBadge = element.status == "1" ? "badge-success" : "badge-danger";
      var text = element.status == "1" ? "Ativo" : "Inativo";
      htmlBody += "<tr>";
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
      htmlBody += `<a href="payments.php?id=${element.id}&nome=${encodeURIComponent(element.nome)}&cpf=${element.cpf}" class="mx-1"><i class='fas fa-dollar-sign'></i></a>`;
      htmlBody += `<a href="#" class=" view-carteirinha" data-toggle="modal" data-target="#carteirinhaModal" data-id="${element.id}" data-nome="${encodeURIComponent(element.nome)}" data-cpf="${element.cpf}" data-estado_civil="${encodeURIComponent(element.estado_civil)}" data-profissao="${encodeURIComponent(element.profissao)}" data-data_nascimento="${encodeURIComponent(element.data_nascimento)}" data-email="${encodeURIComponent(element.email)}" data-telefone="${element.telefone}" data-status="${element.status}" data-endereco="${element.endereco}" data-tipo=""><i class="fa-solid fa-address-card"></i></a>`;
      htmlBody += `</ul>`;
      htmlBody += `</td>`;
      htmlBody += `</tr>`;
    });
  }
  $("#tbody").html(htmlBody);
  $("#table-socios").DataTable();

  $(document).on('click', '.view-carteirinha', function(event) {
    event.preventDefault();
    element = $(this).data();
    openModal(element);
  });

  // document.getElementById('download-image').addEventListener('click', function() {
  //   downloadImage(element, 'card-container');
  // });

  // const btnFisico = document.getElementById('btn-fisico');
  // const btnDigital = document.getElementById('btn-digital');
  // const cardContainer = document.getElementById('card-container');
  // const cardFront = document.getElementById('card-front');
  // const cardBack = document.getElementById('card-back');
  // const qrCodeFront = document.getElementById('qrCodeFront');
  // const qrCodeBack = document.getElementById('qrCodeBack');

  // function mostrarDigital() {
  //   cardBack.classList.add('d-none');
  //   qrCodeFront.classList.remove('d-none');
  //   qrCodeBack.classList.add('d-none');
  // }

  // function mostrarFisico() {
  //   cardBack.classList.remove('d-none');
  //   qrCodeFront.classList.add('d-none');
  //   qrCodeBack.classList.remove('d-none');
  // }

  // btnFisico.addEventListener('click', function() {
  //   mostrarFisico();
  // });

  // btnDigital.addEventListener('click', function() {
  //   mostrarDigital();
  // });

  // mostrarFisico();
}

async function getSQL(url) {
  const response = await fetch(url);
  if (!response.ok) {
    throw new Error(`Erro na requisição: ${response.statusText}`);
  }
  return await response.json();
}

document.addEventListener("DOMContentLoaded", function() {
  const dataNascimentoInput = document.querySelector('input[name="data_nascimento"]');
  const today = new Date().toISOString().split('T')[0];
  dataNascimentoInput.setAttribute('max', today);

  // Verifique se o elemento existe antes de adicionar o evento
  const downloadImageButton = document.getElementById('download-image');
  if (downloadImageButton) {
    downloadImageButton.addEventListener('click', function() {
      downloadImage(element, 'card-container');
    });
  }

  const btnFisico = document.getElementById('btn-fisico');
  const btnDigital = document.getElementById('btn-digital');
  const cardContainer = document.getElementById('card-container');
  const cardFront = document.getElementById('card-front');
  const cardBack = document.getElementById('card-back');
  const qrCodeFront = document.getElementById('qrCodeFront');
  const qrCodeBack = document.getElementById('qrCodeBack');

  function mostrarDigital() {
    if (cardBack) cardBack.classList.add('d-none');
    if (qrCodeFront) qrCodeFront.classList.remove('d-none');
    if (qrCodeBack) qrCodeBack.classList.add('d-none');
  }

  function mostrarFisico() {
    if (cardBack) cardBack.classList.remove('d-none');
    if (qrCodeFront) qrCodeFront.classList.add('d-none');
    if (qrCodeBack) qrCodeBack.classList.remove('d-none');
  }

  if (btnFisico) {
    btnFisico.addEventListener('click', function() {
      mostrarFisico();
    });
  }

  if (btnDigital) {
    btnDigital.addEventListener('click', function() {
      mostrarDigital();
    });
  }

  mostrarFisico();
});

