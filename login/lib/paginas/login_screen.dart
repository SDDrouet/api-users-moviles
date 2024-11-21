import 'package:flutter/material.dart';
import 'package:jwt_decoder/jwt_decoder.dart'; // Librería para decodificar JWT
import 'register_screen.dart';
import 'auth_service.dart'; // Servicio de autenticación
import 'user_list_screen.dart'; // Pantalla de lista de usuarios

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  // Controladores de texto para usuario y contraseña
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  bool isLoading = false; // Indicador de carga

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  // Método para manejar el inicio de sesión
  Future<void> _login() async {
    setState(() {
      isLoading = true; // Muestra el indicador de carga
    });

    try {
      // Llamada al servicio para iniciar sesión
      final token = await AuthService().login(
        _emailController.text.trim(),
        _passwordController.text.trim(),
      );

      // Verifica que el token no esté vacío
      if (token.isEmpty) {
        throw 'Error: Token inválido. Verifica tus credenciales.';
      }

      // Decodifica el token para extraer el nombre de usuario
      final decodedToken = JwtDecoder.decode(token);
      if (!decodedToken.containsKey('username')) {
        throw 'Error: El token no contiene información del usuario.';
      }
      final username = decodedToken['username'];

      // Redirige a la pantalla de usuarios si el login es exitoso
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (context) => UserListScreen(token: token, username: username),
        ),
      );
    } catch (error) {
      // Muestra un mensaje de error si ocurre algo
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Error: $error'),
          backgroundColor: Colors.red,
        ),
      );
    } finally {
      setState(() {
        isLoading = false; // Oculta el indicador de carga
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      body: Center(
        child: Padding(
          padding: const EdgeInsets.symmetric(horizontal: 24.0),
          child: SingleChildScrollView(
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: const [
                    Icon(Icons.mail, color: Colors.blue, size: 28),
                    SizedBox(width: 8),
                    Text(
                      "LOGIN",
                      style: TextStyle(
                        fontSize: 24,
                        fontWeight: FontWeight.bold,
                        color: Colors.blue,
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                const Icon(Icons.person, size: 64, color: Colors.blue),
                const SizedBox(height: 24),
                TextField(
                  controller: _emailController, // Controlador de texto para el email
                  decoration: InputDecoration(
                    labelText: "Usuario",
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    prefixIcon: const Icon(Icons.email),
                  ),
                  keyboardType: TextInputType.emailAddress,
                ),
                const SizedBox(height: 16),
                TextField(
                  controller: _passwordController, // Controlador de texto para la contraseña
                  decoration: InputDecoration(
                    labelText: "Contraseña",
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    prefixIcon: const Icon(Icons.lock),
                  ),
                  obscureText: true, // Oculta la contraseña
                ),
                const SizedBox(height: 24),
                isLoading
                    ? const CircularProgressIndicator() // Indicador de carga
                    : ElevatedButton(
                  onPressed: _login, // Llama al método de inicio de sesión
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.blue,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    padding: const EdgeInsets.symmetric(vertical: 12),
                  ),
                  child: const Text(
                    "LOG IN",
                    style: TextStyle(fontSize: 16, color: Colors.white),
                  ),
                ),
                const SizedBox(height: 16),
                TextButton(
                  onPressed: () {
                    // Redirige a la pantalla de registro
                    Navigator.of(context).push(MaterialPageRoute(
                      builder: (context) =>  RegisterScreen(),
                    ));
                  },
                  child: const Text(
                    "No tienes una cuenta?",
                    style: TextStyle(
                      color: Colors.blue,
                      decoration: TextDecoration.underline,
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
