// Classe responsável pelo controle de acesso dos alunos
class AccessControl {
    constructor() {
        this.accessLog = []; // Array para armazenar o registro de acessos
        this.presentStudents = new Set(); // Conjunto para armazenar alunos presentes
        this.totalEntries = 0; // Contador total de entradas
        this.totalExits = 0; // Contador total de saídas

        this.initializeEventListeners(); // Inicializa os ouvintes de eventos
        this.updateStats(); // Atualiza as estatísticas na interface
    }

    // Inicializa os ouvintes de eventos para o formulário de acesso
    initializeEventListeners() {
        document.getElementById('accessForm').addEventListener('submit', (e) => {
            e.preventDefault(); // Prevê o envio padrão do formulário
            this.handleAccess(); // Chama o método para lidar com o acesso
        });
    }

    // Lida com o registro de acesso
    handleAccess() {
        const studentId = document.getElementById('studentId').value.trim(); // Captura a matrícula do aluno
        const accessType = document.getElementById('accessType').value.trim(); // Captura o tipo de acesso

        // Validação dos campos
        if (!studentId || !accessType) {
            alert('Por favor, preencha todos os campos.'); // Mensagem de erro se campos estiverem vazios
            return;
        }

        // Validação para saída sem entrada registrada
        if (accessType === 'saida' && !this.presentStudents.has(studentId)) {
            alert(`O estudante ${studentId} não pode sair porque não registrou entrada.`); // Mensagem de erro
            return;
        }

        // Criação dos dados do formulário
        const formData = new URLSearchParams();
        formData.append('matricula', studentId);
        formData.append('tipo_acesso', accessType);

        // Envio dos dados para o servidor
        fetch('index.php', { 
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData.toString() // Dados do formulário convertidos para string
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na rede ou servidor: ' + response.statusText);
            }
            return response.json(); // Retorna a resposta em formato JSON
        })
        .then(data => {
            this.processServerResponse(data, studentId, accessType); // Processa a resposta do servidor
        })
        .catch(error => {
            console.error('Erro na requisição:', error); // Log de erro no console
            alert('Erro ao registrar acesso: ' + error.message); // Mensagem de erro ao usuário
        });
    }

    // Processa a resposta do servidor
    processServerResponse(data, studentId, accessType) {
        if (data.status === 'sucesso') {
            // Atualiza o estado conforme o tipo de acesso
            if (accessType === 'entrada') {
                this.presentStudents.add(studentId); // Adiciona o aluno ao conjunto de presentes
                this.totalEntries++; // Incrementa o contador de entradas
                data.mensagem = 'Entrada registrada com sucesso!'; // Mensagem de sucesso
            } else if (accessType === 'saida') {
                this.presentStudents.delete(studentId); // Remove o aluno do conjunto de presentes
                this.totalExits++; // Incrementa o contador de saídas
                data.mensagem = 'Saída registrada com sucesso!'; // Mensagem de sucesso
            }

            // Adiciona o registro de acesso ao log
            this.accessLog.unshift({
                studentId,
                accessType,
                timestamp: new Date() // Marca o timestamp da entrada ou saída
            });
            
            this.updateLog(); // Atualiza a visualização do log
            this.updateStats(); // Atualiza as estatísticas na interface
            this.resetForm(); // Reseta o formulário
            
            alert(data.mensagem); // Exibe a mensagem de sucesso ao usuário
        } else {
            alert(data.mensagem); // Mensagem de erro caso a operação falhe
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
        `).join(''); // Monta a visualização do log
    }

    // Atualiza as estatísticas na interface
    updateStats() {
        document.getElementById('presentCount').textContent = this.presentStudents.size; // Total de alunos presentes
        document.getElementById('totalEntries').textContent = this.totalEntries; // Total de entradas
        document.getElementById('totalExits').textContent = this.totalExits; // Total de saídas
    }

    // Reseta o formulário de entrada
    resetForm() {
        document.getElementById('studentId').value = ''; // Limpa o campo da matrícula
        document.getElementById('accessType').value = ''; // Limpa o campo do tipo de acesso
    }

    // Formata a hora para visualização
    formatTime(date) {
        return date.toLocaleTimeString('pt-BR', {
            hour: '2-digit',
            minute: '2-digit' // Formato de hora e minuto
        });
    }
}

// Inicializa o sistema de controle de acesso
const accessControl = new AccessControl();
