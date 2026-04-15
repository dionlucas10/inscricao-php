/* ============================================
   FORMULÁRIO DE INSCRIÇÃO - INTERAÇÕES JAVASCRIPT
   ============================================ */

// Mapping for friendly display of values
const displayMaps = {
    tiposParticipantes: {
        'estudante': 'Estudante',
        'profissional': 'Profissional',
        'empreendedor': 'Empreendedor',
        'entusiasta': 'Entusiasta de Tecnologia'
    },
    interesses: {
        'web': 'Desenvolvimento Web',
        'mobile': 'Desenvolvimento Mobile',
        'design': 'Design UX/UI',
        'dados': 'Ciência de Dados',
        'cloud': 'Cloud Computing',
        'ia': 'Inteligência Artificial'
    },
    cidades: {
        'sao-paulo': 'São Paulo',
        'rio-janeiro': 'Rio de Janeiro',
        'minas-gerais': 'Minas Gerais',
        'brasilia': 'Brasília',
        'salvador': 'Salvador',
        'recife': 'Recife',
        'outro': 'Outra cidade'
    }
};

// Elementos do DOM
const formularioInscricao = document.getElementById('formularioInscricao');
const tipoParticipanteSelect = document.getElementById('tipoParticipante');
const camposEmpreendedor = document.getElementById('camposEmpreendedor');
const checkboxesInteresse = document.querySelectorAll('.interesse-item');
const contagemInteresse = document.getElementById('contagemInteresse');
const erroInteresse = document.getElementById('erroInteresse');
const textareaMensagem = document.getElementById('mensagem');
const contagemCaracteres = document.getElementById('contagemCaracteres');
const botaoResumo = document.getElementById('botaoResumo');
const modalResumo = new bootstrap.Modal(document.getElementById('modalResumo'));
const conteudoResumo = document.getElementById('conteudoResumo');

// ============================================
// EVENT LISTENERS
// ============================================

// Participant Type Change - Show/Hide Entrepreneur Fields
tipoParticipanteSelect.addEventListener('change', function() {
    if (this.value === 'empreendedor') {
        camposEmpreendedor.style.display = 'block';
        // Exigir campos do empreendedor quando selecionado
        document.getElementById('nomeEmpresa').setAttribute('required', '');
        document.getElementById('areaAtuacao').setAttribute('required', '');
    } else {
        camposEmpreendedor.style.display = 'none';
        // Remover obrigatoriedade quando não for empreendedor
        document.getElementById('nomeEmpresa').removeAttribute('required');
        document.getElementById('areaAtuacao').removeAttribute('required');
    }
});

// Seleção de interesses - máximo 3
checkboxesInteresse.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const totalMarcados = document.querySelectorAll('.interesse-item:checked').length;

        if (totalMarcados > 3) {
            this.checked = false;
            erroInteresse.style.display = 'block';
            setTimeout(() => {
                erroInteresse.style.display = 'none';
            }, 3000);
            return;
        }

        atualizarContagemInteresse();
    });
});

function atualizarContagemInteresse() {
    const checkedCount = document.querySelectorAll('.interesse-item:checked').length;
    contagemInteresse.textContent = `${checkedCount}/3`;

    if (checkedCount === 3) {
        contagemInteresse.classList.add('bg-success');
        contagemInteresse.classList.remove('bg-info');
    } else {
        contagemInteresse.classList.remove('bg-success');
        contagemInteresse.classList.add('bg-info');
    }
}

// Contador de caracteres da mensagem
textareaMensagem.addEventListener('input', function() {
    const currentLength = this.value.length;
    contagemCaracteres.textContent = currentLength;

    // Truncate if exceeds 500 characters
    if (currentLength > 500) {
        this.value = this.value.substring(0, 500);
        contagemCaracteres.textContent = '500';
    }

    // Visual feedback
    if (currentLength > 400) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// Summary Button
botaoResumo.addEventListener('click', function() {
    if (validateForm()) {
        displaySummary();
        modalResumo.show();
    }
});

// ============================================
// FORM VALIDATION
// ============================================

function validateForm() {
    const isValid = formularioInscricao.checkValidity() === false ? false : true;

    // Custom validations
    const interestCheckboxCount = document.querySelectorAll('.interesse-item:checked').length;

    if (interestCheckboxCount === 0) {
        erroInteresse.textContent = 'Selecione pelo menos um interesse.';
        erroInteresse.style.display = 'block';
        return false;
    }

    if (interestCheckboxCount > 3) {
        erroInteresse.textContent = 'Selecione no máximo 3 interesses.';
        erroInteresse.style.display = 'block';
        return false;
    }

    erroInteresse.style.display = 'none';

    // Validate entrepreneur fields if entrepreneur is selected
    if (tipoParticipanteSelect.value === 'empreendedor') {
        const companyName = document.getElementById('nomeEmpresa').value.trim();
        const businessArea = document.getElementById('areaAtuacao').value.trim();

        if (!companyName || !businessArea) {
            alert('Por favor, preencha os campos adicionais de empreendedor.');
            return false;
        }
    }

    if (!isValid) {
        formularioInscricao.classList.add('was-validated');
        return false;
    }

    return true;
}

// ============================================
// DISPLAY SUMMARY
// ============================================

function displaySummary() {
    // Get form values
    const formData = new FormData(formularioInscricao);
    const data = Object.fromEntries(formData);

    // Get selected interests as array
    const selectedInterests = Array.from(document.querySelectorAll('.interesse-item:checked'))
        .map(checkbox => displayMaps.interesses[checkbox.value])
        .join(', ');

    // Get participant type display
    const participantTypeDisplay = displayMaps.tiposParticipantes[data.tipoParticipante];
    const cityDisplay = displayMaps.cidades[data.cidade];

    // Build summary HTML
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

    // Add entrepreneur info if applicable
    if (data.tipoParticipante === 'empreendedor') {
        summaryHTML += `
            <div class="resumo-item">
                <span class="rotulo-resumo">Nome da Empresa:</span>
                <span class="valor-resumo">${escapeHtml(data.nomeEmpresa || 'Não informado')}</span>
            </div>
            <div class="resumo-item">
                <span class="rotulo-resumo">Área de Atuação:</span>
                <span class="valor-resumo">${escapeHtml(data.areaAtuacao || 'Não informado')}</span>
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

    // Add message if provided
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

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Reset Form
function resetForm() {
    if (confirm('Tem certeza que deseja limpar todos os dados do formulário?')) {
        formularioInscricao.reset();
        formularioInscricao.classList.remove('was-validated');
        camposEmpreendedor.style.display = 'none';
        contagemCaracteres.textContent = '0';
        contagemInteresse.textContent = '0/3';
        contagemInteresse.classList.add('bg-info');
        contagemInteresse.classList.remove('bg-success');
        erroInteresse.style.display = 'none';
        textareaMensagem.classList.remove('is-invalid');
    }
}

// ============================================
// PHONE FORMATTING
// ============================================

// Optional: Add phone formatting
document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    
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

// ============================================
// FORM SUBMISSION
// ============================================

// Remove prevent default - permitir envio normal do formulário
// O formulário será enviado via POST para o PHP processar

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Formulário de Inscrição - TechConf 2026 carregado com sucesso!');
    
    // Remove was-validated class on input
    const inputs = formularioInscricao.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
