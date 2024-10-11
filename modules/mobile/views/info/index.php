	
Просмотр Чат<br>
api/chat-history/index<br>
[<span style="color: blue;">GET</span>]<br>
ОТДАЕТ:<br>
[ChatHistory, ChatHistory, ...]<br>
<br>
Создание Чат<br>
api/chat-history/create<br>
[<span style="color: red;">POST</span>]<br>
-text (Текст) Текст<br>
-user_id (Связь со справочником) Пользователь<br>
-sender_id (Связь со справочником) Отправитель<br>
-created_at_id (Дата и время) Дата отправки<br>
-view (Чекбокс) Просмотрено<br>
ОТДАЕТ:<br>
{success: true}<br>
<br>
Изменить Чат<br>
api/chat-history/update<br>
[<span style="color: red;">POST</span>]<br>
-text (Текст) Текст<br>
-user_id (Связь со справочником) Пользователь<br>
-sender_id (Связь со справочником) Отправитель<br>
-created_at_id (Дата и время) Дата отправки<br>
-view (Чекбокс) Просмотрено<br>
ОТДАЕТ:<br>
{success: true}<br>
<br>
Просмотр Чат<br>
api/chat-history/view<br>
[<span style="color: blue;">GET</span>]<br>
- id (Число) ID<br>
ОТДАЕТ:<br>
{success: true}<br>
<br>
Удалить Чат<br>
api/chat-history/delete<br>
[<span style="color: red;">POST</span>]<br>
- id (Число) ID<br>
ОТДАЕТ:<br>
{success: true}<br>
<br>
