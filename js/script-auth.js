class AuthSystem {
    constructor() {
      this.users = [];
      this.currentUser = null;
      this.initializeEventListeners();
    }
  
    initializeEventListeners() {
      document.getElementById('loginForm').addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleLogin();
      });
  
      document.getElementById('registerForm').addEventListener('submit', (e) => {
        e.preventDefault();
        this.handleRegister();
      });
    }
  
    handleLogin() {
      const email = document.getElementById('loginEmail').value;
      const password = document.getElementById('loginPassword').value;
      
      const user = this.users.find(u => u.email === email && u.password === password);
      
      if (user) {
        this.currentUser = user;
        window.location.href = '/cadastro.html'; 
      } else {
        document.getElementById('loginError').style.display = 'block';
      }
    }
  
    handleRegister() {
      const newUser = {
        name: document.getElementById('registerName').value,
        email: document.getElementById('registerEmail').value,
        password: document.getElementById('registerPassword').value,
        role: document.getElementById('userRole').value
      };
  
      if (this.users.some(u => u.email === newUser.email)) {
        document.getElementById('registerError').textContent = 'E-mail já cadastrado';
        document.getElementById('registerError').style.display = 'block';
        return;
      }
  
      this.users.push(newUser);
      switchTab('login');
      document.getElementById('registerForm').reset();
    }
  }
  
  function switchTab(tab) {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const tabs = document.querySelectorAll('.tab');
  
    if (tab === 'login') {
      loginForm.style.display = 'block';
      registerForm.style.display = 'none';
      tabs[0].classList.add('active');
      tabs[1].classList.remove('active');
    } else {
      loginForm.style.display = 'none';
      registerForm.style.display = 'block';
      tabs[0].classList.remove('active');
      tabs[1].classList.add('active');
    }
  }
  
  const authSystem = new AuthSystem();
  
  // Add sample user for testing
  authSystem.users.push({
    name: 'Admin',
    email: 'admin@faculdade.com',
    password: 'admin123',
    role: 'admin'
  });