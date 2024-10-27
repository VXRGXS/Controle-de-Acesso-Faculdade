// Classe responsável pelo gerenciamento de alunos
class StudentManagement {
    constructor() {
      this.students = []; // Array para armazenar alunos
      this.courses = new Set(); // Conjunto para armazenar cursos únicos

      this.initializeEventListeners(); // Inicializa os ouvintes de eventos
      this.loadStudents(); // Carrega os alunos do banco de dados
      this.updateStats(); // Atualiza as estatísticas
    }

    // Inicializa os ouvintes de eventos para o formulário
    initializeEventListeners() {
      document.getElementById('studentForm').addEventListener('submit', (e) => {
        e.preventDefault(); // Previne o comportamento padrão do formulário
        this.handleStudentRegistration(); // Manipula o registro de aluno
      });
    }

    // Carrega alunos do banco de dados
    async loadStudents() {
        try {
            const response = await fetch('listar_alunos.php'); // Requisição para listar alunos
            const alunos = await response.json(); // Converte resposta em JSON

            // Adiciona os alunos ao array de estudantes
            this.students = alunos.map(aluno => ({
                matricula: aluno.matricula,
                nome: aluno.nome,
                curso: aluno.curso,
                semestre: aluno.semestre
            }));

            // Atualiza o conjunto de cursos com os cursos existentes
            alunos.forEach(aluno => this.courses.add(aluno.curso));

            this.updateStudentsList(); // Atualiza a lista de alunos exibida
            this.updateStats(); // Atualiza as estatísticas
        } catch (error) {
            console.error('Erro ao carregar os alunos:', error); // Log de erro
        }
    }
    
    // Manipula o registro de um novo aluno
    async handleStudentRegistration() {
      const student = {
        matricula: null,
        nome: document.getElementById('studentName').value,
        curso: document.getElementById('course').value,
        semestre: document.getElementById('semester').value
      };

      // Enviar dados para cadastro.php usando fetch
      try {
        const response = await fetch('cadastro.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json' // Define o tipo de conteúdo como JSON
          },
          body: JSON.stringify(student) // Converte o aluno em JSON
        });

        const result = await response.json(); // Converte resposta em JSON

        if (result.status === "sucesso") {
            // Atualiza a lista e estatísticas no front-end
            student.matricula = result.matricula; // Matrícula gerada pelo banco
            this.students.push(student); // Adiciona o aluno ao array
            this.courses.add(student.curso); // Adiciona o novo curso ao conjunto
            this.updateStudentsList(); // Atualiza a lista de alunos
            this.updateStats(); // Atualiza as estatísticas após adicionar o novo aluno
            this.resetForm(); // Reseta o formulário
            alert(result.mensagem); // Exibe mensagem de sucesso
        }
        
      } catch (error) {
        console.error('Erro ao enviar os dados:', error); // Log de erro
        alert("Erro ao cadastrar aluno."); // Exibe mensagem de erro
      }
    }

    // Atualiza a lista de alunos exibida na interface
    updateStudentsList() {
      const listContainer = document.getElementById('studentsList');
      listContainer.innerHTML = this.students.map(student => `
        <div class="student-item">
          <div class="student-info">
            <strong>${student.nome}</strong>
            <span>Matrícula: ${student.matricula}</span>
            <span>Curso: ${student.curso} - ${student.semestre}º Semestre</span>
          </div>
          <div class="student-actions">
            <button class="btn-edit" onclick="studentManagement.editStudent('${student.matricula}')">Editar</button>
            <button class="btn-delete" onclick="studentManagement.deleteStudent('${student.matricula}')">Excluir</button>
          </div>
        </div>
      `).join(''); // Converte array de alunos em HTML
    }

    // Atualiza as estatísticas de alunos e cursos
    updateStats() {
        document.getElementById('totalStudents').textContent = this.students.length; // Atualiza total de alunos
        document.getElementById('activeCourses').textContent = this.courses.size; // Atualiza total de cursos
    }
    
    // Reseta o formulário após o cadastro
    resetForm() {
      document.getElementById('studentForm').reset(); // Reseta o formulário
    }

    // Manipula a edição de um aluno
    editStudent(studentId) {
      const student = this.students.find(s => s.matricula === studentId); // Encontra o aluno
      if (student) {
        document.getElementById('studentId').value = student.matricula;
        document.getElementById('studentName').value = student.nome;
        document.getElementById('course').value = student.curso;
        document.getElementById('semester').value = student.semestre;

        // Remove o aluno da lista para atualizar
        this.students = this.students.filter(s => s.matricula !== studentId);
        this.updateStudentsList(); // Atualiza a lista de alunos
        this.updateStats(); // Atualiza as estatísticas
      }
    }

    // Manipula a exclusão de um aluno
    deleteStudent(studentId) {
      this.students = this.students.filter(s => s.matricula !== studentId); // Remove o aluno da lista
      this.updateStudentsList(); // Atualiza a lista de alunos
      this.updateStats(); // Atualiza as estatísticas
    }
}

// Cria uma instância do gerenciamento
const studentManagement = new StudentManagement();
