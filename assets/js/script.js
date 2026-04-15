

// Mapeamento para exibir os valores de forma amigável e compreensível ao usuário
const displayMaps = {
  tiposParticipantes: {
    estudante: "Estudante",
    profissional: "Profissional",
    empreendedor: "Empreendedor",
    entusiasta: "Entusiasta de Tecnologia",
  },
  interesses: {
    web: "Desenvolvimento Web",
    mobile: "Desenvolvimento Mobile",
    design: "Design UX/UI",
    dados: "Ciência de Dados",
    cloud: "Cloud Computing",
    ia: "Inteligência Artificial",
  },
  cidades: {
    "sao-paulo": "São Paulo",
    "rio-janeiro": "Rio de Janeiro",
    "minas-gerais": "Minas Gerais",
    brasilia: "Brasília",
    salvador: "Salvador",
    recife: "Recife",
    outro: "Outra cidade",
  },
};

// DOM
const formularioInscricao = document.getElementById("formularioInscricao");
const tipoParticipanteSelect = document.getElementById("tipoParticipante");
const camposEmpreendedor = document.getElementById("camposEmpreendedor");
const checkboxesInteresse = document.querySelectorAll(".interesse-item");
const contagemInteresse = document.getElementById("contagemInteresse");
const erroInteresse = document.getElementById("erroInteresse");
const textareaMensagem = document.getElementById("mensagem");
const contagemCaracteres = document.getElementById("contagemCaracteres");
const botaoResumo = document.getElementById("botaoResumo");
const modalResumo = new bootstrap.Modal(document.getElementById("modalResumo"));
const conteudoResumo = document.getElementById("conteudoResumo");


// Escondendo o campo de empreendedor
// Alteração do Tipo de Participante - Mostrar/Ocultar Campos Específicos para Empreendedores
tipoParticipanteSelect.addEventListener("change", function () {
  if (this.value === "empreendedor") {
    camposEmpreendedor.style.display = "block";

    document.getElementById("nomeEmpresa").setAttribute("required", "");
    document.getElementById("areaAtuacao").setAttribute("required", "");
  } else {
    camposEmpreendedor.style.display = "none";

    document.getElementById("nomeEmpresa").removeAttribute("required");
    document.getElementById("areaAtuacao").removeAttribute("required");
  }
});

// Seleção de Interesses, limitando a 3 seleções
checkboxesInteresse.forEach((checkbox) => {
  checkbox.addEventListener("change", function () {
    const totalMarcados = document.querySelectorAll(
      ".interesse-item:checked",
    ).length;

    if (totalMarcados > 3) {
      this.checked = false;
      erroInteresse.style.display = "block";
      setTimeout(() => {
        erroInteresse.style.display = "none";
      }, 3000);
      return;
    }

    atualizarContagemInteresse();
  });
});

function atualizarContagemInteresse() {
  const checkedCount = document.querySelectorAll(
    ".interesse-item:checked",
  ).length;
  contagemInteresse.textContent = `${checkedCount}/3`;

  if (checkedCount === 3) {
    contagemInteresse.classList.add("bg-success");
    contagemInteresse.classList.remove("bg-info");
  } else {
    contagemInteresse.classList.remove("bg-success");
    contagemInteresse.classList.add("bg-info");
  }
}

// Contador de Caracteres da Mensagem
textareaMensagem.addEventListener("input", function () {
  const currentLength = this.value.length;
  contagemCaracteres.textContent = currentLength;

  // 500 caracteres para respeitar o limite estabelecido
  if (currentLength > 500) {
    this.value = this.value.substring(0, 500);
    contagemCaracteres.textContent = "500";
  }

  // Fornecendo feedback visual ao usuário sobre o limite de caracteres
  if (currentLength > 400) {
    this.classList.add("is-invalid");
  } else {
    this.classList.remove("is-invalid");
  }
});

// Botão de Resumo
botaoResumo.addEventListener("click", function () {
  if (validateForm()) {
    displaySummary();
    modalResumo.show();
  }
});

// Validação do Formulário 

function validateForm() {
  const isValid = formularioInscricao.checkValidity() === false ? false : true;

  const interestCheckboxCount = document.querySelectorAll(
    ".interesse-item:checked",
  ).length;

  if (interestCheckboxCount === 0) {
    erroInteresse.textContent = "Selecione pelo menos um interesse.";
    erroInteresse.style.display = "block";
    return false;
  }

  if (interestCheckboxCount > 3) {
    erroInteresse.textContent = "Selecione no máximo 3 interesses.";
    erroInteresse.style.display = "block";
    return false;
  }

  erroInteresse.style.display = "none";

  // Validar campos específicos do empreendedor
  if (tipoParticipanteSelect.value === "empreendedor") {
    const companyName = document.getElementById("nomeEmpresa").value.trim();
    const businessArea = document.getElementById("areaAtuacao").value.trim();

    if (!companyName || !businessArea) {
      alert("Por favor, preencha os campos adicionais de empreendedor.");
      return false;
    }
  }

  if (!isValid) {
    formularioInscricao.classList.add("was-validated");
    return false;
  }

  return true;
}

// Exibição do Resumo 
function displaySummary() {
  // Obtendo os valores preenchidos
  const formData = new FormData(formularioInscricao);
  const data = Object.fromEntries(formData);

  // Coletando os interesses selecionados
  const selectedInterests = Array.from(
    document.querySelectorAll(".interesse-item:checked"),
  )
    .map((checkbox) => displayMaps.interesses[checkbox.value])
    .join(", ");

  // Obtendo a descrição amigável
  const participantTypeDisplay =
    displayMaps.tiposParticipantes[data.tipoParticipante];
  const cityDisplay = displayMaps.cidades[data.cidade];

  // Construindo o HTML do resumo para exibir as informações de forma organizada
  let summaryHTML = `
        <div class="resumo-sucesso">
            <i class="bi bi-check-circle-fill"></i>
            <span>Inscrição revisada com sucesso! Todos os dados estão corretos.</span>
        </div>

        <div class="resumo-secao">
            <h6><i class="bi bi-person-fill"></i> Dados Pessoais</h6>
            <div class="resumo-item">
                <span class="rotulo-resumo">Nome Completo:</span>
                <span class="valor-resumo">${escapeHtml(data.nomeCompleto)}</span>
            </div>
            <div class="resumo-item">
                <span class="rotulo-resumo">E-mail:</span>
                <span class="valor-resumo">${escapeHtml(data.email)}</span>
            </div>
            <div class="resumo-item">
                <span class="rotulo-resumo">Telefone:</span>
                <span class="valor-resumo">${escapeHtml(data.telefone)}</span>
            </div>
            <div class="resumo-item">
                <span class="rotulo-resumo">Cidade:</span>
                <span class="valor-resumo">${cityDisplay}</span>
            </div>
        </div>

        <div class="resumo-secao">
            <h6><i class="bi bi-briefcase-fill"></i> Tipo de Participante</h6>
            <div class="resumo-item">
                <span class="rotulo-resumo">Participante Como:</span>
                <span class="valor-resumo">
                    <span class="badge bg-primary">${participantTypeDisplay}</span>
                </span>
            </div>
    `;

  // Construindo seções adicionais para empreendedores
  if (data.tipoParticipante === "empreendedor") {
    summaryHTML += `
            <div class="resumo-item">
                <span class="rotulo-resumo">Nome da Empresa:</span>
                <span class="valor-resumo">${escapeHtml(data.nomeEmpresa || "Não informado")}</span>
            </div>
            <div class="resumo-item">
                <span class="rotulo-resumo">Área de Atuação:</span>
                <span class="valor-resumo">${escapeHtml(data.areaAtuacao || "Não informado")}</span>
            </div>
        `;
  }

  summaryHTML += `
        </div>

        <div class="resumo-secao">
            <h6><i class="bi bi-star-fill"></i> Interesses</h6>
            <div class="resumo-item">
                <span class="rotulo-resumo">Áreas de Interesse:</span>
                <span class="valor-resumo">
                    ${selectedInterests}
                </span>
            </div>
        </div>
    `;

  if (data.mensagem && data.mensagem.trim()) {
    summaryHTML += `
            <div class="resumo-secao">
                <h6><i class="bi bi-chat-left-text-fill"></i> Mensagem</h6>
                <div class="resumo-item">
                    <span class="rotulo-resumo">Observações:</span>
                    <span class="valor-resumo" style="word-break: break-word;">
                        ${escapeHtml(data.mensagem)}
                    </span>
                </div>
            </div>
        `;
  }

  summaryHTML += `
        <div class="resumo-secao">
            <h6><i class="bi bi-clipboard-check-fill"></i> Aceites</h6>
            <div class="resumo-item">
                <span class="valor-resumo">
                    <i class="bi bi-check-circle-fill text-success"></i> 
                    Termos e condições aceitos
                </span>
            </div>
        </div>

        <div class="alert alert-info mt-3" role="alert">
            <i class="bi bi-info-circle"></i> 
            <strong>Próximo Passo:</strong> Você receberá um e-mail de confirmação em breve. 
            A inscrição será considerada completa após a confirmação do link.
        </div>
    `;

  conteudoResumo.innerHTML = summaryHTML;
}


// Escape HTML para evitar XSS
function escapeHtml(text) {
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };
  return text.replace(/[&<>"']/g, (m) => map[m]);
}

// Resetar o formulário
function resetForm() {
  if (confirm("Tem certeza que deseja limpar todos os dados do formulário?")) {
    formularioInscricao.reset();
    formularioInscricao.classList.remove("was-validated");
    camposEmpreendedor.style.display = "none";
    contagemCaracteres.textContent = "0";
    contagemInteresse.textContent = "0/3";
    contagemInteresse.classList.add("bg-info");
    contagemInteresse.classList.remove("bg-success");
    erroInteresse.style.display = "none";
    textareaMensagem.classList.remove("is-invalid");
  }
}

// Adcionaro o telefone formatado
document.getElementById("telefone").addEventListener("input", function (e) {
  let value = e.target.value.replace(/\D/g, "");

  if (value.length > 0) {
    if (value.length <= 2) {
      value = `(${value}`;
    } else if (value.length <= 7) {
      value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
    } else {
      value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7, 11)}`;
    }
  }

  e.target.value = value;
});


document.addEventListener("DOMContentLoaded", function () {
  console.log(
    "Formulário de Inscrição - FutureTech 2026 carregado com sucesso!",
  );

  const inputs = formularioInscricao.querySelectorAll(
    ".form-control, .form-select",
  );
  inputs.forEach((input) => {
    input.addEventListener("input", function () {
      this.classList.remove("is-invalid");
    });
  });
});
