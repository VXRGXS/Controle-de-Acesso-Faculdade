// Classe responsável pelo controle de acesso dos alunos
class AccessControl {
    constructor() {
        this.accessLog = [];
        this.totalEntries = 0;
        this.totalExits = 0;
        this.totalPresentes = 0;

        this.fetchDailyStats();
        this.initializeEventListeners();
    }

    // Busca as estatísticas do banco de dados
    fetchDailyStats() {
        fetch('listar_acessos.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao buscar estatísticas: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                // Atualiza os contadores com os dados do banco de dados
                this.totalEntries = data.entradas;
                this.totalExits = data.saidas;
                this.totalPresentes = data.presentes;
                this.updateStats(); // Atualiza a interface com os dados recebidos
            })
            .catch(error => console.error('Erro ao buscar estatísticas:', error));
    }

    // Inicializa os ouvintes de eventos para o formulário de acesso
    initializeEventListeners() {
        document.getElementById('accessForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleAccess();
        });
    }

    // Lida com o registro de acesso
    handleAccess() {
        const studentId = document.getElementById('studentId').value.trim();
        const accessType = document.getElementById('accessType').value.trim();
    
        if (!studentId || !accessType) {
            alert('Por favor, preencha todos os campos.');
            return;
        }
    
        const formData = new URLSearchParams();
        formData.append('matricula', studentId);
        formData.append('tipo_acesso', accessType);
    
        fetch('index.php', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData.toString()
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na requisição: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            alert(data.mensagem); // Exibe a mensagem do servidor
            if (data.status === 'sucesso') {
                this.processServerResponse(data, studentId, accessType);
            }
        })
        .catch(error => {
            console.error('Erro na requisição:', error);
            alert('Erro ao registrar acesso: ' + error.message);
        });
    }
    

    // Processa a resposta do servidor
    processServerResponse(data, studentId, accessType) {
        if (data.status === 'sucesso') {
            if (accessType === 'entrada') {
                this.totalEntries++;
                this.totalPresentes++;
            } else if (accessType === 'saida') {
                this.totalExits++;
                this.totalPresentes--;
            }

            this.accessLog.unshift({
                studentId,
                accessType,
                timestamp: new Date()
            });

            this.updateLog();
            this.updateStats();
            this.resetForm();
        } 
    }

    // Atualiza a visualização do log de acessos
    updateLog() {
        const logContainer = document.getElementById('accessLog');
        logContainer.innerHTML = this.accessLog.map(entry => `
            <div class="log-entry">
                <span>
                    <span class="status-indicator ${entry.accessType === 'entrada' ? 'status-active' : 'status-inactive'}"></span>
                    Matrícula: ${entry.studentId}
                </span>
                <span>${entry.accessType.toUpperCase()} - ${this.formatTime(entry.timestamp)}</span>
            </div>
        `).join('');
    }

    // Atualiza as estatísticas na interface
    updateStats() {
        document.getElementById('presentCount').textContent = this.totalPresentes;
        document.getElementById('totalEntries').textContent = this.totalEntries;
        document.getElementById('totalExits').textContent = this.totalExits;
    }

    // Reseta o formulário de entrada
    resetForm() {
        document.getElementById('studentId').value = '';
        document.getElementById('accessType').value = '';
    }

    // Formata a hora para visualização
    formatTime(date) {
        return date.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }
}

// Inicializa o sistema de controle de acesso
const accessControl = new AccessControl();
