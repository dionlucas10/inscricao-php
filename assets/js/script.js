/* ============================================
   REGISTRATION FORM - JAVASCRIPT INTERACTIONS
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

// DOM Elements
const form = document.getElementById('registrationForm');
const participantTypeSelect = document.getElementById('participantType');
const entrepreneurFields = document.getElementById('entrepreneurFields');
const interestCheckboxes = document.querySelectorAll('.interest-checkbox');
const interestCount = document.getElementById('interestCount');
const interestError = document.getElementById('interestError');
const messageTextarea = document.getElementById('message');
const charCount = document.getElementById('charCount');
const summaryBtn = document.getElementById('summaryBtn');
const summaryModal = new bootstrap.Modal(document.getElementById('summaryModal'));
const summaryContent = document.getElementById('summaryContent');

// ============================================
// EVENT LISTENERS
// ============================================

// Participant Type Change - Show/Hide Entrepreneur Fields
participantTypeSelect.addEventListener('change', function() {
    if (this.value === 'empreendedor') {
        entrepreneurFields.style.display = 'block';
        // Add required to company name when entrepreneur is selected
        document.getElementById('companyName').setAttribute('required', '');
        document.getElementById('businessArea').setAttribute('required', '');
    } else {
        entrepreneurFields.style.display = 'none';
        // Remove required from company name when not entrepreneur
        document.getElementById('companyName').removeAttribute('required');
        document.getElementById('businessArea').removeAttribute('required');
    }
});

// Interest Checkboxes - Max 3 Selection
interestCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const checkedCount = document.querySelectorAll('.interest-checkbox:checked').length;

        // If trying to select more than 3, uncheck this one
        if (checkedCount > 3) {
            this.checked = false;
            interestError.style.display = 'block';
            setTimeout(() => {
                interestError.style.display = 'none';
            }, 3000);
            return;
        }

        // Update counter
        updateInterestCount();
    });
});

function updateInterestCount() {
    const checkedCount = document.querySelectorAll('.interest-checkbox:checked').length;
    interestCount.textContent = `${checkedCount}/3`;

    // Add visual feedback
    if (checkedCount === 3) {
        interestCount.classList.add('bg-success');
        interestCount.classList.remove('bg-info');
    } else {
        interestCount.classList.remove('bg-success');
        interestCount.classList.add('bg-info');
    }
}

// Message Character Counter
messageTextarea.addEventListener('input', function() {
    const currentLength = this.value.length;
    charCount.textContent = currentLength;

    // Truncate if exceeds 500 characters
    if (currentLength > 500) {
        this.value = this.value.substring(0, 500);
        charCount.textContent = '500';
    }

    // Visual feedback
    if (currentLength > 400) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// Summary Button
summaryBtn.addEventListener('click', function() {
    if (validateForm()) {
        displaySummary();
        summaryModal.show();
    }
});

// ============================================
// FORM VALIDATION
// ============================================

function validateForm() {
    const isValid = form.checkValidity() === false ? false : true;

    // Custom validations
    const interestCheckboxCount = document.querySelectorAll('.interest-checkbox:checked').length;

    if (interestCheckboxCount === 0) {
        interestError.textContent = 'Selecione pelo menos um interesse.';
        interestError.style.display = 'block';
        return false;
    }

    if (interestCheckboxCount > 3) {
        interestError.textContent = 'Selecione no máximo 3 interesses.';
        interestError.style.display = 'block';
        return false;
    }

    interestError.style.display = 'none';

    // Validate entrepreneur fields if entrepreneur is selected
    if (participantTypeSelect.value === 'empreendedor') {
        const companyName = document.getElementById('companyName').value.trim();
        const businessArea = document.getElementById('businessArea').value.trim();

        if (!companyName || !businessArea) {
            alert('Por favor, preencha os campos adicionais de empreendedor.');
            return false;
        }
    }

    if (!isValid) {
        form.classList.add('was-validated');
        return false;
    }

    return true;
}

// ============================================
// DISPLAY SUMMARY
// ============================================

function displaySummary() {
    // Get form values
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    // Get selected interests as array
    const selectedInterests = Array.from(document.querySelectorAll('.interest-checkbox:checked'))
        .map(checkbox => displayMaps.interesses[checkbox.value])
        .join(', ');

    // Get participant type display
    const participantTypeDisplay = displayMaps.tiposParticipantes[data.participantType];
    const cityDisplay = displayMaps.cidades[data.city];

    // Build summary HTML
    let summaryHTML = `
        <div class="summary-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>Inscrição revisada com sucesso! Todos os dados estão corretos.</span>
        </div>

        <div class="summary-section">
            <h6><i class="bi bi-person-fill"></i> Dados Pessoais</h6>
            <div class="summary-item">
                <span class="summary-label">Nome Completo:</span>
                <span class="summary-value">${escapeHtml(data.fullName)}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">E-mail:</span>
                <span class="summary-value">${escapeHtml(data.email)}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Telefone:</span>
                <span class="summary-value">${escapeHtml(data.phone)}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Cidade:</span>
                <span class="summary-value">${cityDisplay}</span>
            </div>
        </div>

        <div class="summary-section">
            <h6><i class="bi bi-briefcase-fill"></i> Tipo de Participante</h6>
            <div class="summary-item">
                <span class="summary-label">Participante Como:</span>
                <span class="summary-value">
                    <span class="badge bg-primary">${participantTypeDisplay}</span>
                </span>
            </div>
    `;

    // Add entrepreneur info if applicable
    if (data.participantType === 'empreendedor') {
        summaryHTML += `
            <div class="summary-item">
                <span class="summary-label">Nome da Empresa:</span>
                <span class="summary-value">${escapeHtml(data.companyName || 'Não informado')}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Área de Atuação:</span>
                <span class="summary-value">${escapeHtml(data.businessArea || 'Não informado')}</span>
            </div>
        `;
    }

    summaryHTML += `
        </div>

        <div class="summary-section">
            <h6><i class="bi bi-star-fill"></i> Interesses</h6>
            <div class="summary-item">
                <span class="summary-label">Áreas de Interesse:</span>
                <span class="summary-value">
                    ${selectedInterests}
                </span>
            </div>
        </div>
    `;

    // Add message if provided
    if (data.message && data.message.trim()) {
        summaryHTML += `
            <div class="summary-section">
                <h6><i class="bi bi-chat-left-text-fill"></i> Mensagem</h6>
                <div class="summary-item">
                    <span class="summary-label">Observações:</span>
                    <span class="summary-value" style="word-break: break-word;">
                        ${escapeHtml(data.message)}
                    </span>
                </div>
            </div>
        `;
    }

    summaryHTML += `
        <div class="summary-section">
            <h6><i class="bi bi-clipboard-check-fill"></i> Aceites</h6>
            <div class="summary-item">
                <span class="summary-value">
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

    summaryContent.innerHTML = summaryHTML;
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
        form.reset();
        form.classList.remove('was-validated');
        entrepreneurFields.style.display = 'none';
        charCount.textContent = '0';
        interestCount.textContent = '0/3';
        interestCount.classList.add('bg-info');
        interestCount.classList.remove('bg-success');
        interestError.style.display = 'none';
        messageTextarea.classList.remove('is-invalid');
    }
}

// ============================================
// PHONE FORMATTING
// ============================================

// Optional: Add phone formatting
document.getElementById('phone').addEventListener('input', function(e) {
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

form.addEventListener('submit', function(e) {
    if (!validateForm()) {
        e.preventDefault(); // Bloqueia envio se validação falhar
        form.classList.add('was-validated');
    }
    // Se passou na validação, deixa o formulário submeter normalmente
});

// ============================================
// INITIALIZATION
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Formulário de Inscrição - TechConf 2026 carregado com sucesso!');
    
    // Remove was-validated class on input
    const inputs = form.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });
});
