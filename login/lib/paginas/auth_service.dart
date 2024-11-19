import 'dart:convert';
import 'package:http/http.dart' as http;

class AuthService {
  final String baseUrl = 'http://localhost/users-api/api'; // Cambia la URL base si es necesario

  Future<String> login(String email, String password) async {
    final url = Uri.parse('$baseUrl/login');
    final response = await http.post(
      url,
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'username': email,  // Si la API espera username, cambia aquí.
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final body = jsonDecode(response.body);
      return body['token']; // Retorna el token si el login es exitoso.
    } else {
      final body = jsonDecode(response.body);
      throw Exception(body['message'] ?? 'Error desconocido');
    }
  }

  Future<String> register(String username, String email, String password) async {
    final url = Uri.parse('$baseUrl/users');
    final response = await http.post(
      url,
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'username': username,
        'email': email,
        'password': password,
      }),
    );

    if (response.statusCode == 201) {
      return 'Usuario registrado exitosamente';
    } else {
      final body = jsonDecode(response.body);
      throw Exception(body['message'] ?? 'Error desconocido');
    }
  }
}
