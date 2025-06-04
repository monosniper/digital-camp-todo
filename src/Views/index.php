<!doctype html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Digital Camp Todo</title>
	<link rel="stylesheet" href="/dist/app.css">
</head>
<body>
<div class="container">
	<h1>Todo List</h1>

	<form action="/todos" method="post" class="todo-form">
		<div class="form-group">
			<label for="title">Название</label>
			<input type="text" name="title" id="title" required>
		</div>

		<div class="form-group">
			<label for="description">Описание</label>
			<textarea name="description" id="description" rows="4"></textarea>
		</div>

		<div class="form-group">
			<label for="deadline">Дедлайн</label>
			<input
					type="datetime-local"
					name="deadline"
					id="deadline"
			>
		</div>

		<button type="submit">Сохранить</button>
	</form>

	<ul class="todo-list">
        <?php foreach ($todos as $todo): ?>
	        <li class="todo-item <?= $todo->is_done ? 'done' : '' ?>">
		        <div class="content">
			        <div class="title"><?= htmlspecialchars($todo->title) ?></div>
			        <div class="description"><?= nl2br(htmlspecialchars($todo->description)) ?></div>
			        <div class="deadline">
				        Срок: <?= $todo->deadline ? $todo->deadline->format('d.m.Y H:i') : '—' ?>
			        </div>
		        </div>

		        <?php if (!$todo->is_done) { ?>
			        <div class="actions">
				        <form action="/todos/<?= $todo->id ?>" method="POST" style="display:inline;">
					        <input type="hidden" name="_method" value="PATCH">
					        <input type="hidden" name="is_done" value="<?= $todo->is_done ? 0 : 1 ?>">
					        <button type="submit" title="Отметить выполнено/не выполнено">✓</button>
				        </form>

				        <form action="/todos/<?= $todo->id ?>" method="POST" style="display:inline;">
					        <input type="hidden" name="_method" value="DELETE">
					        <button type="submit" title="Удалить задачу">✕</button>
				        </form>
			        </div>
		        <?php } ?>
			</li>
        <?php endforeach; ?>
	</ul>
</div>

<script src="/dist/app.js" defer></script>
</body>
</html>