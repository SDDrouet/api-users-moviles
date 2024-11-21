import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'user_form_screen.dart';

class UserListScreen extends StatefulWidget {
  final String token;
  final String username;

  const UserListScreen({Key? key, required this.token, required this.username}) : super(key: key);

  @override
  _UserListScreenState createState() => _UserListScreenState();
}

class _UserListScreenState extends State<UserListScreen> {
  List users = [];
  bool isLoading = true;

  @override
  void initState() {
    super.initState();
    fetchUsers();
  }

  // Método para obtener la lista de usuarios
  Future<void> fetchUsers() async {
    setState(() {
      isLoading = true;
    });

    try {
      final response = await http.get(
        Uri.parse('http://localhost/users-api/api/users'), // Asegúrate de usar tu IP o dominio correcto
        headers: {
          'Authorization': 'Bearer ${widget.token}',
        },
      );

      if (response.statusCode == 200) {
        setState(() {
          users = json.decode(response.body);
        });
      } else {
        _showSnackBar('Error al cargar usuarios', success: false);
      }
    } catch (e) {
      _showSnackBar('Error de red: $e', success: false);
    } finally {
      setState(() {
        isLoading = false;
      });
    }
  }

  // Método para eliminar un usuario
  Future<void> deleteUser(int id) async {
    try {
      final response = await http.delete(
        Uri.parse('http://localhost/users-api/api/users/$id'), // Ruta para eliminar usuario
        headers: {
          'Authorization': 'Bearer ${widget.token}',
        },
      );

      if (response.statusCode == 200) {
        _showSnackBar('Usuario eliminado correctamente');
        fetchUsers(); // Actualiza la lista después de eliminar
      } else {
        _showSnackBar('Error al eliminar usuario', success: false);
      }
    } catch (e) {
      _showSnackBar('Error de red: $e', success: false);
    }
  }

  // Método para mostrar mensajes
  void _showSnackBar(String message, {bool success = true}) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
        content: Text(message),
        backgroundColor: success ? Colors.green : Colors.red,
      ),
    );
  }

  // Confirmación antes de eliminar un usuario
  void _confirmDelete(int id) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Confirmar eliminación'),
        content: const Text('¿Estás seguro de que quieres eliminar este usuario?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancelar'),
          ),
          TextButton(
            onPressed: () {
              Navigator.pop(context);
              deleteUser(id);
            },
            child: const Text('Eliminar'),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('¡Bienvenido, ${widget.username}!'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => UserFormScreen(token: widget.token),
                ),
              ).then((_) => fetchUsers());
            },
          ),
        ],
      ),
      body: isLoading
          ? const Center(child: CircularProgressIndicator()) // Indicador de carga
          : users.isEmpty
          ? const Center(child: Text('No hay usuarios registrados'))
          : ListView.builder(
        itemCount: users.length,
        itemBuilder: (context, index) {
          final user = users[index];
          return ListTile(
            title: Text(user['username']),
            subtitle: Text(user['email']),
            trailing: Row(
              mainAxisSize: MainAxisSize.min,
              children: [
                IconButton(
                  icon: const Icon(Icons.edit),
                  onPressed: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (context) => UserFormScreen(
                          token: widget.token,
                          user: user,
                        ),
                      ),
                    ).then((_) => fetchUsers());
                  },
                ),
                IconButton(
                  icon: const Icon(Icons.delete),
                  onPressed: () => _confirmDelete(user['id']),
                ),
              ],
            ),
          );
        },
      ),
    );
  }
}
