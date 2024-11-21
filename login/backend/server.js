const express = require('express');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();
app.use(bodyParser.json());
app.use(cors());

const SECRET_KEY = "123"; // Cambia esto por una clave más segura
let users = []; // Simulamos una base de datos con un arreglo

// Ruta para registrar usuarios
app.post('/register', async (req, res) => {
const { username, email, password } = req.body;
const hashedPassword = awa it bcrypt.hash(password, 10);
users.push({ username, email, password: hashedPassword });
res.json({ message: 'Usuario registrado con éxito' });
});

// Ruta para iniciar sesión
app.post('/login', async (req, res) => {
const { email, password } = req.body;
const user = users.find(u => u.email === email);
if (!user) return res.status(400).json({ message: 'Usuario no encontrado' });

const isPasswordValid = await bcrypt.compare(password, user.password);
if (!isPasswordValid) return res.status(400).json({ message: 'Contraseña incorrecta' });

const token = jwt.sign({ email: user.email }, SECRET_KEY, { expiresIn: '1h' });
res.json({ token });
});

app.listen(3000, () => {
console.log('Servidor corriendo en http://localhost:3000');
});
