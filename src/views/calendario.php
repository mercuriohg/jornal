<?php
require_once __DIR__ . '/../controller/NewsController.php';
$eventList = NewsController::getNewsByType('evento', 100);

$year = isset($_GET['y']) ? (int) $_GET['y'] : (int) date('Y');
$month = isset($_GET['m']) ? (int) $_GET['m'] : (int) date('n');
if ($month < 1) {
    $month = 1;
} elseif ($month > 12) {
    $month = 12;
}

$currentDate = new DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month));
$daysInMonth = (int) $currentDate->format('t');
$startWeekday = (int) $currentDate->format('N');

$monthNames = [
    1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
    5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
    9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro',
];

$eventsByDay = [];
foreach ($eventList as $event) {
    $eventDate = $event['event_date'] ?? $event['created_at'];
    $dayKey = date('Y-m-d', $eventDate);
    if (!isset($eventsByDay[$dayKey])) {
        $eventsByDay[$dayKey] = [];
    }
    $eventsByDay[$dayKey][] = $event;
}

$prevMonth = $currentDate->modify('-1 month');
$nextMonth = $currentDate->modify('+1 month');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Eventos</title>
    <link rel="stylesheet" href="/assets/style/index.css">
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
      integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="icon" href="/assets/img/malal_icon.ico" type="image/x-icon">
</head>
<body>
    <?php include 'components/header.php'; ?>
    <?php include 'components/sidebar.php'; ?>

    <main class="calendar-page">
        <section class="calendar-hero">
            <div class="calendar-hero-content">
                <span>CALENDÁRIO</span>
                <h1>Próximos eventos do Grêmio Estudantil</h1>
                <p>Aqui você encontra todos os eventos publicados pelo painel administrativo. Clique para ver os detalhes de cada evento.</p>
            </div>
        </section>

        <section class="calendar-month">
            <div class="calendar-month-header">
                <div>
                    <span>Calendário Mensal</span>
                    <h2><?= htmlspecialchars($monthNames[$month]) ?> <?= htmlspecialchars($year) ?></h2>
                </div>
                <div class="calendar-nav">
                    <a href="/calendario?m=<?= $prevMonth->format('n') ?>&y=<?= $prevMonth->format('Y') ?>">&laquo; Mês anterior</a>
                    <a href="/calendario?m=<?= $nextMonth->format('n') ?>&y=<?= $nextMonth->format('Y') ?>">Próximo mês &raquo;</a>
                </div>
            </div>

            <div class="calendar-grid-month">
                <?php foreach (['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'] as $weekday): ?>
                    <div class="calendar-weekday"><?= $weekday ?></div>
                <?php endforeach; ?>

                <?php for ($blank = 1; $blank < $startWeekday; $blank++): ?>
                    <div class="calendar-day calendar-day-empty"></div>
                <?php endfor; ?>

                <?php for ($day = 1; $day <= $daysInMonth; $day++):
                    $currentKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $dayEvents = $eventsByDay[$currentKey] ?? [];
                ?>
                    <div class="calendar-day <?= $dayEvents ? 'calendar-day-has-events' : '' ?>">
                        <div class="day-number"><?= $day ?></div>
                        <?php if (!empty($dayEvents)): ?>
                            <div class="day-events">
                                <?php foreach ($dayEvents as $event): ?>
                                    <a class="day-event" href="/noticia?id=<?= urlencode($event['id']) ?>" title="<?= htmlspecialchars($event['title']) ?>">
                                        <?= htmlspecialchars(mb_strimwidth($event['title'], 0, 34, '...')) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </section>

        <section class="calendar-grid">
            <?php if (empty($eventList)): ?>
                <article class="calendar-empty">
                    <h2>Sem eventos cadastrados</h2>
                    <p>Publique eventos no painel de administração para que eles apareçam aqui no calendário.</p>
                </article>
            <?php else: ?>
                <?php foreach ($eventList as $event): ?>
                    <?php $eventDate = $event['event_date'] ?? $event['created_at']; ?>
                    <article class="event-card">
                        <div class="event-card-date">
                            <strong><?= htmlspecialchars(date('d/m/Y', $eventDate)) ?></strong>
                            <span><?= htmlspecialchars(ucfirst($event['tag'])) ?></span>
                        </div>
                        <div class="event-card-body">
                            <h3><?= htmlspecialchars($event['title']) ?></h3>
                            <p><?= htmlspecialchars($event['summary']) ?></p>
                        </div>
                        <div class="event-card-actions">
                            <a class="event-link" href="/noticia?id=<?= urlencode($event['id']) ?>">Ver detalhes</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'components/footer.php'; ?>
    <script> 
        const menuToggle = document.getElementById('menu-toggle');
        const sidebar = document.getElementById('sidebar');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            menuToggle.classList.toggle('fa-bars');
            menuToggle.classList.toggle('fa-times');
        });
    </script>
    <div vw class="enabled">
    <div vw-access-button class="active"></div>
    <div vw-plugin-wrapper>
        <div class="vw-plugin-top-wrapper"></div>
    </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>
</html>
