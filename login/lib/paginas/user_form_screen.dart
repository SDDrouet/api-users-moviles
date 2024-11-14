import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class UserFormScreen extends StatefulWidget {
  final String token;
  final Map? user;

  UserFormScreen({required this.token, this.user});

  @override
  _UserFormScreenState createState() => _UserFormScreenState();
}

class _UserFormScreenState extends State<UserFormScreen> {
  final TextEditingController usernameController = TextEditingController();
  final TextEditingController emailController = TextEditingController();

  @override
  void initState() {
    super.initState();
    if (widget.user != null) {
      usernameController.text = widget.user!['username'];
      emailController.text = widget.user!['email'];
    }
  }

  Future<void> saveUser() async {
    final url = widget.user == null
        ? 'http://yourapiurl.com/users'
        : 'http://yourapiurl.com/users/${widget.user!['id']}';

    final response = await http.post(
      Uri.parse(url),
      headers: {
        'Authorization': 'Bearer ${widget.token}',
      },
      body: {
        'username': usernameController.text,
        'email': emailController.text,
      },
    );

    if (response.statusCode == 200) {
      Navigator.pop(context);
    } else {
      print('Error al guardar usuario');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(widget.user == null ? "Crear Usuario" : "Actualizar Usuario")),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          children: [
            TextField(
              controller: usernameController,
              decoration: InputDecoration(labelText: "Nombre de Usuario"),
            ),
            TextField(
              controller: emailController,
              decoration: InputDecoration(labelText: "Email"),
            ),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: saveUser,
              child: Text("Guardar"),
            ),
          ],
        ),
      ),
    );
  }
}
