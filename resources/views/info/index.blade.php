@extends('layouts.app')

@section('title', 'Info - Vier op een Rij')

@section('content')
<div class="info-container">
    <section class="info-section">
        <h2>Over deze applicatie</h2>
        <p>
            Deze applicatie is volledig gebouwd door een <strong>autonome AI-agent</strong>
            die zelfstandig draait zonder menselijke tussenkomst. De agent is gebaseerd op het
            <a href="https://www.anthropic.com/engineering/effective-harnesses-for-long-running-agents" target="_blank">"Effective Harnesses for Long-Running Agents"</a>
            patroon van Anthropic, oorspronkelijk ontwikkeld voor het bouwen van React-applicaties.
        </p>
        <p>
            Onze implementatie is aangepast voor het bouwen van <strong>Laravel-applicaties</strong>
            met PHP 8.3, MariaDB en Playwright voor browser-automatisering.
        </p>
    </section>

    <section class="info-section">
        <h2>Two-Agent Architecture</h2>
        <p>
            De agent gebruikt een <strong>two-agent pattern</strong> om grote projecten te kunnen bouwen
            die niet in één context window passen:
        </p>
        <ul>
            <li>
                <strong>Initializer Agent</strong> - Draait één keer aan het begin van het project.
                Leest de app-specificatie, creëert het Laravel-project, genereert een <code>feature_list.json</code>
                met alle te bouwen features, en zet de basis op (database, routes, layout).
            </li>
            <li>
                <strong>Coding Agent</strong> - Draait in opeenvolgende sessies. Implementeert één feature per keer,
                test met Playwright browser-automatisering, markeert features als "passing" wanneer geverifieerd,
                en commit de voortgang.
            </li>
        </ul>
        <p>
            Door incrementeel te werken en voortgang bij te houden in bestanden zoals <code>claude-progress.txt</code>
            en <code>feature_list.json</code>, kan de agent projecten bouwen die veel groter zijn dan één context window.
        </p>
    </section>

    <section class="info-section">
        <h2>Claude Code SDK</h2>
        <p>
            De agent is gebouwd met de <a href="https://docs.anthropic.com/en/docs/claude-code-sdk" target="_blank">Claude Code SDK</a>,
            die context management biedt via compaction capabilities. Dit stelt de agent in staat om over
            willekeurig lange tijdsperiodes te werken zonder dat de beschikbare context opraakt.
        </p>
        <p>
            De SDK biedt ook <strong>MCP (Model Context Protocol)</strong> integratie, waardoor de agent
            tools kan gebruiken zoals Playwright voor browser-automatisering. Hiermee test de agent features
            zoals een eindgebruiker zou doen, in plaats van alleen te vertrouwen op unit tests.
        </p>
    </section>

    <section class="info-section">
        <h2>Deployment</h2>
        <p>
            Deze applicatie wordt automatisch gedeployd met <a href="https://caprover.com/" target="_blank">CapRover</a>,
            een open-source Platform-as-a-Service (PaaS). CapRover maakt het eenvoudig om applicaties te deployen
            en beheren via een web-interface of CLI.
        </p>
    </section>

    <section class="info-section">
        <h2>Over het spel</h2>
        <p>
            <strong>Vier op een Rij</strong> (ook bekend als Connect Four) is een strategisch bordspel voor twee spelers.
            Het spel wordt gespeeld op een verticaal bord van 7 kolommen en 6 rijen.
        </p>
        <p>
            <strong>Spelregels:</strong>
        </p>
        <ul>
            <li>Spelers laten om de beurt een schijf in een kolom vallen</li>
            <li>De schijf valt naar de laagste beschikbare positie in die kolom</li>
            <li>De eerste speler die vier schijven op een rij krijgt (horizontaal, verticaal of diagonaal) wint</li>
            <li>Als het bord vol is zonder winnaar, eindigt het spel in gelijkspel</li>
        </ul>
        <p>
            <strong>Moeilijkheidsgraden:</strong>
        </p>
        <ul>
            <li><span class="badge badge-easy">EASY</span> - De AI speelt 66% van de tijd optimaal</li>
            <li><span class="badge badge-medium">MEDIUM</span> - De AI speelt 90% van de tijd optimaal</li>
            <li><span class="badge badge-hard">HARD</span> - De AI speelt altijd optimaal met minimax-algoritme en alpha-beta pruning</li>
        </ul>
    </section>

    <section class="info-section">
        <h2>Technologie</h2>

        <h3>Applicatie</h3>
        <div class="tech-stack">
            <div class="tech-item">
                <strong>Backend:</strong> Laravel 12.x (PHP 8.3)
            </div>
            <div class="tech-item">
                <strong>Database:</strong> MariaDB 10.11
            </div>
            <div class="tech-item">
                <strong>Frontend:</strong> Vanilla JavaScript, CSS3
            </div>
            <div class="tech-item">
                <strong>Build tool:</strong> Vite
            </div>
        </div>

        <h3>Agentic Build Tools</h3>
        <div class="tech-stack">
            <div class="tech-item">
                <strong>Agent SDK:</strong> Claude Code SDK (Python)
            </div>
            <div class="tech-item">
                <strong>Browser Testing:</strong> Playwright MCP
            </div>
            <div class="tech-item">
                <strong>Container:</strong> DevContainer (Docker)
            </div>
            <div class="tech-item">
                <strong>Deployment:</strong> CapRover
            </div>
        </div>

        <h3>AI Models</h3>
        <div class="tech-stack">
            <div class="tech-item">
                <strong>Initializer Agent:</strong> Claude Sonnet 4
            </div>
            <div class="tech-item">
                <strong>Coding Agent:</strong> Claude Sonnet 4
            </div>
        </div>
    </section>

    <section class="info-section">
        <h2>Bronnen</h2>
        <ul>
            <li><a href="https://www.anthropic.com/engineering/effective-harnesses-for-long-running-agents" target="_blank">Effective Harnesses for Long-Running Agents</a> - Anthropic Engineering Blog</li>
            <li><a href="https://docs.anthropic.com/en/docs/claude-code-sdk" target="_blank">Claude Code SDK Documentation</a></li>
            <li><a href="https://caprover.com/" target="_blank">CapRover - Build your own PaaS</a></li>
        </ul>
    </section>
</div>
@endsection
