import 'package:flutter/material.dart';

void main() {
  runApp(const GameDiaryApp());
}

// КРОК 5: Оформлення та базова стилізація
class GameDiaryApp extends StatelessWidget {
  const GameDiaryApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Game Diary Lab',
      theme: ThemeData(
        brightness: Brightness.dark,
        scaffoldBackgroundColor: const Color(0xFF121212),
        appBarTheme: const AppBarTheme(backgroundColor: Color(0xFF1E1E1E)),
        elevatedButtonTheme: ElevatedButtonThemeData(
          style: ElevatedButton.styleFrom(
            foregroundColor: Colors.black,
            backgroundColor: Colors.white,
          ),
        ),
      ),
      home: const MainScreen(),
    );
  }
}

// КРОК 2 та КРОК 4: Головний екран (StatefulWidget)
class MainScreen extends StatefulWidget {
  const MainScreen({super.key});

  @override
  State<MainScreen> createState() => _MainScreenState();
}

class _MainScreenState extends State<MainScreen> {
  // Змінні для керування станом (Крок 4)
  final TextEditingController _reviewController = TextEditingController();
  bool _isCompleted = false;
  String _selectedGame = "Cyberpunk 2077"; // Вибраний елемент за замовчуванням

  // Дані для списку
  final List<String> _games = [
    "Cyberpunk 2077",
    "The Last of Us Part I",
    "Silent Hill 2",
    "Red Dead Redemption 2"
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Game Diary'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('Ваш список ігор:', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 10),
            
            // Тіло: ListView з кількома елементами-картками (Крок 2)
            Expanded(
              child: ListView.builder(
                itemCount: _games.length,
                itemBuilder: (context, index) {
                  return Card(
                    color: _selectedGame == _games[index] ? Colors.grey[700] : Colors.grey[900],
                    child: ListTile(
                      title: Text(_games[index]),
                      onTap: () {
                        // Оновлення стану при виборі елемента (Крок 4)
                        setState(() {
                          _selectedGame = _games[index]; 
                        });
                      },
                    ),
                  );
                },
              ),
            ),
            const SizedBox(height: 10),
            
            // Текстове поле взаємодії (Крок 4)
            TextField(
              controller: _reviewController,
              decoration: const InputDecoration(
                labelText: 'Коротка замітка до гри',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 10),
            
            // Перемикач взаємодії (Крок 4)
            SwitchListTile(
              title: const Text('Гра пройдена?'),
              value: _isCompleted,
              onChanged: (bool value) {
                setState(() {
                  _isCompleted = value; // Збереження значення в стані
                });
              },
            ),
            const SizedBox(height: 10),
            
            // Кнопка переходу на другий екран (Крок 2)
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  // Виклик Navigator.push з передачею даних через конструктор (Крок 3)
                  Navigator.push(
                    context,
                    MaterialPageRoute(
                      builder: (context) => SecondScreen(
                        gameTitle: _selectedGame,
                        review: _reviewController.text,
                        isCompleted: _isCompleted,
                      ),
                    ),
                  );
                },
                child: const Text('Детальніше'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// КРОК 3: Другий екран та навігація
class SecondScreen extends StatelessWidget {
  // Дані, що приймаються через конструктор
  final String gameTitle;
  final String review;
  final bool isCompleted;

  const SecondScreen({
    super.key,
    required this.gameTitle,
    required this.review,
    required this.isCompleted,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      // AppBar автоматично має стрілку "Назад" (Крок 3)
      appBar: AppBar(
        title: const Text('Деталі гри'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Назва: $gameTitle', style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
            const SizedBox(height: 20),
            Text('Статус: ${isCompleted ? "Пройдено" : "В процесі"}', style: const TextStyle(fontSize: 18)),
            const SizedBox(height: 20),
            const Text('Ваша замітка:', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 8),
            Text(review.isEmpty ? "Немає замітки" : review, style: const TextStyle(fontSize: 16)),
            const Spacer(),
            
            // Альтернативна кнопка "Назад" (Крок 3)
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: () {
                  Navigator.pop(context); // Повернення назад
                },
                child: const Text('Назад до списку'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}