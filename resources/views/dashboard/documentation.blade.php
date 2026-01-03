@extends('base')
@section('title', 'Documentation')

@section('styles')
    <style>
        .doc-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .doc-header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 20px;
            padding: 40px;
            color: white;
            margin-bottom: 32px;
            box-shadow: 0 10px 40px rgba(99, 102, 241, 0.3);
        }

        .doc-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .doc-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .doc-nav {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 32px;
            position: sticky;
            top: 20px;
        }

        .doc-nav-title {
            font-size: 14px;
            font-weight: 700;
            color: #6366f1;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 16px;
        }

        .doc-nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .doc-nav-list li {
            margin-bottom: 8px;
        }

        .doc-nav-list a {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            color: #4b5563;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .doc-nav-list a:hover {
            background: #f3f4f6;
            color: #6366f1;
            transform: translateX(4px);
        }

        .doc-nav-list a svg {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            opacity: 0.7;
        }

        .doc-section {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .doc-section-header {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e5e7eb;
        }

        .doc-section-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 16px;
        }

        .doc-section-icon.blue {
            background: linear-gradient(135deg, #dbeafe 0%, #e0e7ff 100%);
            color: #4f46e5;
        }

        .doc-section-icon.green {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
        }

        .doc-section-icon.purple {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            color: #7c3aed;
        }

        .doc-section-icon.orange {
            background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
            color: #ea580c;
        }

        .doc-section-icon.red {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
        }

        .doc-section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        .doc-subsection {
            margin-bottom: 28px;
        }

        .doc-subsection:last-child {
            margin-bottom: 0;
        }

        .doc-subsection h4 {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }

        .doc-subsection h4::before {
            content: '';
            width: 4px;
            height: 20px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 2px;
            margin-right: 12px;
        }

        .doc-text {
            color: #4b5563;
            line-height: 1.7;
            margin-bottom: 16px;
        }

        .doc-list {
            list-style: none;
            padding: 0;
            margin: 0 0 16px 0;
        }

        .doc-list li {
            display: flex;
            align-items: flex-start;
            padding: 10px 0;
            color: #4b5563;
            border-bottom: 1px solid #f3f4f6;
        }

        .doc-list li:last-child {
            border-bottom: none;
        }

        .doc-list li svg {
            width: 20px;
            height: 20px;
            color: #6366f1;
            margin-right: 12px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .doc-tip {
            background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
            border-left: 4px solid #6366f1;
            padding: 16px 20px;
            border-radius: 0 12px 12px 0;
            margin: 16px 0;
        }

        .doc-tip-title {
            font-weight: 600;
            color: #4338ca;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .doc-tip-title svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }

        .doc-tip p {
            color: #4338ca;
            margin: 0;
            font-size: 14px;
        }

        .doc-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-left: 4px solid #f59e0b;
            padding: 16px 20px;
            border-radius: 0 12px 12px 0;
            margin: 16px 0;
        }

        .doc-warning-title {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .doc-warning-title svg {
            width: 18px;
            height: 18px;
            margin-right: 8px;
        }

        .doc-warning p {
            color: #92400e;
            margin: 0;
            font-size: 14px;
        }

        .doc-step {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
            padding: 16px;
            background: #f9fafb;
            border-radius: 12px;
        }

        .doc-step-number {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .doc-step-content h5 {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .doc-step-content p {
            color: #6b7280;
            font-size: 14px;
            margin: 0;
        }

        .field-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }

        .field-table th,
        .field-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .field-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .field-table td {
            color: #4b5563;
            font-size: 14px;
        }

        .field-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge.required {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge.optional {
            background: #d1fae5;
            color: #059669;
        }

        .version-info {
            text-align: center;
            padding: 24px;
            color: #9ca3af;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    <div class="flex h-screen overflow-hidden">
        @include('layout.sidebar')

        <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
            <div class="doc-container">
                <!-- Header -->
                <div class="doc-header">
                    <h1>📖 Documentation AmbuGaz</h1>
                    <p>Guide complet d'utilisation de toutes les fonctionnalités du logiciel de gestion pour ambulanciers
                    </p>
                </div>

                <div class="grid grid-cols-12 gap-6">
                    <!-- Navigation latérale -->
                    <div class="col-span-3">
                        <div class="doc-nav">
                            <div class="doc-nav-title">Sommaire</div>
                            <ul class="doc-nav-list">
                                <li>
                                    <a href="#dashboard">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Tableau de bord
                                    </a>
                                </li>
                                <li>
                                    <a href="#salaries">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Gestion des salariés
                                    </a>
                                </li>
                                <li>
                                    <a href="#patients">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Gestion des patients
                                    </a>
                                </li>
                                <li>
                                    <a href="#vehicules">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                        </svg>
                                        Gestion des véhicules
                                    </a>
                                </li>
                                <li>
                                    <a href="#desinfection">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        Désinfection
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="col-span-9">
                        <!-- Section Tableau de bord -->
                        <section id="dashboard" class="doc-section">
                            <div class="doc-section-header">
                                <div class="doc-section-icon blue">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <h3 class="doc-section-title">Tableau de bord</h3>
                            </div>

                            <div class="doc-subsection">
                                <h4>Vue d'ensemble</h4>
                                <p class="doc-text">
                                    Le tableau de bord est la page d'accueil de votre espace de travail. Il vous offre une
                                    vue synthétique de l'activité de votre entreprise et vous permet d'accéder rapidement
                                    aux informations essentielles.
                                </p>
                            </div>

                            <div class="doc-subsection">
                                <h4>Informations affichées</h4>
                                <ul class="doc-list">
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span><strong>Statistiques d'entreprise :</strong> Nombre total de factures,
                                            factures en attente, factures payées</span>
                                    </li>
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span><strong>Chiffre d'affaires :</strong> Total des revenus encaissés et montants
                                            en attente de paiement</span>
                                    </li>
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <span><strong>État du stock :</strong> Nombre total d'articles en stock et alertes
                                            pour les articles en rupture</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="doc-tip">
                                <div class="doc-tip-title">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Astuce
                                </div>
                                <p>Consultez régulièrement votre tableau de bord pour suivre l'évolution de votre activité
                                    et anticiper les besoins de réapprovisionnement.</p>
                            </div>
                        </section>

                        <!-- Section Gestion des salariés -->
                        <section id="salaries" class="doc-section">
                            <div class="doc-section-header">
                                <div class="doc-section-icon purple">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <h3 class="doc-section-title">Gestion des salariés</h3>
                            </div>

                            <div class="doc-subsection">
                                <h4>Présentation</h4>
                                <p class="doc-text">
                                    Le module de gestion des salariés vous permet d'administrer l'ensemble du personnel de
                                    votre entreprise. Vous pouvez créer, modifier et supprimer des fiches salariés, ainsi
                                    que leur attribuer des rôles spécifiques.
                                </p>
                            </div>

                            <div class="doc-subsection">
                                <h4>Ajouter un salarié</h4>
                                <div class="doc-step">
                                    <div class="doc-step-number">1</div>
                                    <div class="doc-step-content">
                                        <h5>Accéder au formulaire</h5>
                                        <p>Cliquez sur le bouton "Ajouter un salarié" en haut à droite de la page.</p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">2</div>
                                    <div class="doc-step-content">
                                        <h5>Remplir les informations</h5>
                                        <p>Saisissez le nom, prénom, email et le rôle du salarié dans le formulaire.</p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">3</div>
                                    <div class="doc-step-content">
                                        <h5>Enregistrer</h5>
                                        <p>Cliquez sur "Enregistrer" pour créer la fiche du salarié.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="doc-subsection">
                                <h4>Champs du formulaire</h4>
                                <table class="field-table">
                                    <thead>
                                        <tr>
                                            <th>Champ</th>
                                            <th>Description</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Nom</strong></td>
                                            <td>Nom de famille du salarié</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Prénom</strong></td>
                                            <td>Prénom du salarié</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td>Adresse email professionnelle (sert d'identifiant de connexion)</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Rôle</strong></td>
                                            <td>Niveau d'accès : Propriétaire, Administrateur ou Employé</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="doc-subsection">
                                <h4>Rôles disponibles</h4>
                                <ul class="doc-list">
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span><strong>Chef d'entreprise (owner) :</strong> Accès complet à toutes les
                                            fonctionnalités, gestion des paramètres de l'entreprise</span>
                                    </li>
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span><strong>Administrateur (admin) :</strong> Accès étendu pour la gestion
                                            courante, sans les paramètres sensibles</span>
                                    </li>
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span><strong>Employé (employee) :</strong> Accès limité aux fonctionnalités de base
                                            nécessaires à son activité</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="doc-subsection">
                                <h4>Modifier un salarié</h4>
                                <p class="doc-text">
                                    Pour modifier les informations d'un salarié, cliquez sur le bouton "Voir" dans la
                                    colonne Actions du tableau. Dans la fenêtre qui s'ouvre, cliquez sur "Modifier" pour
                                    activer l'édition des champs, puis cliquez sur "Enregistrer" pour sauvegarder vos
                                    modifications.
                                </p>
                            </div>

                            <div class="doc-subsection">
                                <h4>Supprimer un salarié</h4>
                                <p class="doc-text">
                                    Pour supprimer un salarié, cliquez sur l'icône poubelle dans la colonne Actions. Une
                                    fenêtre de confirmation s'affichera pour valider votre choix.
                                </p>
                                <div class="doc-warning">
                                    <div class="doc-warning-title">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Attention
                                    </div>
                                    <p>La suppression d'un salarié est définitive. Assurez-vous de ne plus avoir besoin de
                                        cet utilisateur avant de confirmer.</p>
                                </div>
                            </div>
                        </section>

                        <!-- Section Gestion des patients -->
                        <section id="patients" class="doc-section">
                            <div class="doc-section-header">
                                <div class="doc-section-icon green">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="doc-section-title">Gestion des patients</h3>
                            </div>

                            <div class="doc-subsection">
                                <h4>Présentation</h4>
                                <p class="doc-text">
                                    Ce module vous permet de gérer la base de données de vos patients. Vous pouvez
                                    enregistrer leurs informations personnelles, coordonnées et numéro de sécurité sociale
                                    pour faciliter la création des factures et le suivi des transports.
                                </p>
                            </div>

                            <div class="doc-subsection">
                                <h4>Ajouter un patient</h4>
                                <div class="doc-step">
                                    <div class="doc-step-number">1</div>
                                    <div class="doc-step-content">
                                        <h5>Ouvrir le formulaire</h5>
                                        <p>Cliquez sur "Ajouter un patient" pour ouvrir la fenêtre de création.</p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">2</div>
                                    <div class="doc-step-content">
                                        <h5>Saisir les informations</h5>
                                        <p>Remplissez les champs obligatoires (nom, prénom) et les informations
                                            optionnelles.</p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">3</div>
                                    <div class="doc-step-content">
                                        <h5>Valider</h5>
                                        <p>Cliquez sur "Enregistrer" pour ajouter le patient à votre base de données.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="doc-subsection">
                                <h4>Champs du formulaire</h4>
                                <table class="field-table">
                                    <thead>
                                        <tr>
                                            <th>Champ</th>
                                            <th>Description</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Nom</strong></td>
                                            <td>Nom de famille du patient</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Prénom</strong></td>
                                            <td>Prénom du patient</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date de naissance</strong></td>
                                            <td>Date de naissance au format JJ/MM/AAAA</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Téléphone</strong></td>
                                            <td>Numéro de téléphone du patient</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>N° Sécurité sociale</strong></td>
                                            <td>Numéro de sécurité sociale à 15 chiffres</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Adresse</strong></td>
                                            <td>Rue, code postal et ville du patient</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="doc-tip">
                                <div class="doc-tip-title">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Astuce
                                </div>
                                <p>Renseignez le numéro de sécurité sociale pour faciliter la télétransmission des factures
                                    à l'Assurance Maladie.</p>
                            </div>
                        </section>

                        <!-- Section Gestion des véhicules -->
                        <section id="vehicules" class="doc-section">
                            <div class="doc-section-header">
                                <div class="doc-section-icon orange">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2" />
                                    </svg>
                                </div>
                                <h3 class="doc-section-title">Gestion des véhicules</h3>
                            </div>

                            <div class="doc-subsection">
                                <h4>Présentation</h4>
                                <p class="doc-text">
                                    Le module de gestion des véhicules vous permet de gérer votre flotte d'ambulances, VSL
                                    et taxis. Suivez les informations administratives, les dates d'agrément ARS et l'état de
                                    service de chaque véhicule.
                                </p>
                            </div>

                            <div class="doc-subsection">
                                <h4>Ajouter un véhicule</h4>
                                <div class="doc-step">
                                    <div class="doc-step-number">1</div>
                                    <div class="doc-step-content">
                                        <h5>Ouvrir le formulaire</h5>
                                        <p>Cliquez sur "Ajouter un véhicule" pour accéder au formulaire de création.</p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">2</div>
                                    <div class="doc-step-content">
                                        <h5>Informations générales</h5>
                                        <p>Renseignez le nom, le type (ambulance, VSL, taxi), l'immatriculation et la
                                            catégorie.</p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">3</div>
                                    <div class="doc-step-content">
                                        <h5>Dates de service</h5>
                                        <p>Indiquez les dates de mise en service et de fin de service prévue.</p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">4</div>
                                    <div class="doc-step-content">
                                        <h5>Agrément ARS</h5>
                                        <p>Saisissez le numéro d'agrément ARS ainsi que ses dates de validité.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="doc-subsection">
                                <h4>Champs du formulaire</h4>
                                <table class="field-table">
                                    <thead>
                                        <tr>
                                            <th>Champ</th>
                                            <th>Description</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Nom du véhicule</strong></td>
                                            <td>Identifiant interne (ex: "Ambulance A", "VSL 1")</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Type</strong></td>
                                            <td>Ambulance, VSL ou Taxi</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Immatriculation</strong></td>
                                            <td>Numéro de plaque d'immatriculation</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Numéro VIN</strong></td>
                                            <td>Numéro d'identification du véhicule (17 caractères)</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Catégorie</strong></td>
                                            <td>Catégorie du véhicule (A, B ou C)</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>En service</strong></td>
                                            <td>Indique si le véhicule est actuellement opérationnel</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date de mise en service</strong></td>
                                            <td>Date de début d'exploitation du véhicule</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date de fin de service</strong></td>
                                            <td>Date prévue de retrait du véhicule</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>N° agrément ARS</strong></td>
                                            <td>Numéro d'agrément délivré par l'ARS</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dates agrément ARS</strong></td>
                                            <td>Période de validité de l'agrément</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="doc-warning">
                                <div class="doc-warning-title">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Important
                                </div>
                                <p>Veillez à mettre à jour les dates d'agrément ARS avant expiration pour rester en
                                    conformité avec la réglementation.</p>
                            </div>
                        </section>

                        <!-- Section Désinfection -->
                        <section id="desinfection" class="doc-section">
                            <div class="doc-section-header">
                                <div class="doc-section-icon green">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <h3 class="doc-section-title">Désinfection des véhicules</h3>
                            </div>

                            <div class="doc-subsection">
                                <h4>Présentation</h4>
                                <p class="doc-text">
                                    Le module de désinfection vous permet de tracer toutes les opérations de désinfection
                                    effectuées sur vos véhicules. Cette traçabilité est essentielle pour respecter les
                                    normes d'hygiène et répondre aux contrôles des autorités sanitaires.
                                </p>
                            </div>

                            <div class="doc-subsection">
                                <h4>Enregistrer une désinfection</h4>
                                <div class="doc-step">
                                    <div class="doc-step-number">1</div>
                                    <div class="doc-step-content">
                                        <h5>Nouvelle désinfection</h5>
                                        <p>Cliquez sur "Nouvelle désinfection" pour ouvrir le formulaire d'enregistrement.
                                        </p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">2</div>
                                    <div class="doc-step-content">
                                        <h5>Sélectionner le véhicule</h5>
                                        <p>Choisissez le véhicule concerné dans la liste déroulante (seuls les véhicules en
                                            service sont affichés).</p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">3</div>
                                    <div class="doc-step-content">
                                        <h5>Renseigner les détails</h5>
                                        <p>Indiquez la date, le type de désinfection, le protocole utilisé et le produit.
                                        </p>
                                    </div>
                                </div>
                                <div class="doc-step">
                                    <div class="doc-step-number">4</div>
                                    <div class="doc-step-content">
                                        <h5>Ajouter des remarques</h5>
                                        <p>Si nécessaire, ajoutez des observations dans le champ remarques.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="doc-subsection">
                                <h4>Types de désinfection</h4>
                                <ul class="doc-list">
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span><strong>Quotidienne :</strong> Désinfection de routine réalisée chaque jour
                                            après utilisation du véhicule</span>
                                    </li>
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span><strong>Hebdomadaire :</strong> Désinfection approfondie réalisée une fois par
                                            semaine</span>
                                    </li>
                                    <li>
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span><strong>En profondeur :</strong> Désinfection complète incluant toutes les
                                            surfaces et équipements</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="doc-subsection">
                                <h4>Champs du formulaire</h4>
                                <table class="field-table">
                                    <thead>
                                        <tr>
                                            <th>Champ</th>
                                            <th>Description</th>
                                            <th>Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Véhicule</strong></td>
                                            <td>Véhicule concerné par la désinfection</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date de désinfection</strong></td>
                                            <td>Date à laquelle la désinfection a été effectuée</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Type</strong></td>
                                            <td>Quotidienne, Hebdomadaire ou En profondeur</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Référence protocole</strong></td>
                                            <td>Code ou référence du protocole de désinfection utilisé</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Produit utilisé</strong></td>
                                            <td>Nom commercial du produit désinfectant</td>
                                            <td><span class="badge required">Obligatoire</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Remarques</strong></td>
                                            <td>Observations complémentaires (anomalies, problèmes rencontrés...)</td>
                                            <td><span class="badge optional">Optionnel</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="doc-tip">
                                <div class="doc-tip-title">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Conseil réglementaire
                                </div>
                                <p>Conservez un historique complet des désinfections pour chaque véhicule. Ces informations
                                    peuvent être demandées lors des contrôles ARS ou en cas de problème sanitaire.</p>
                            </div>

                            <div class="doc-subsection">
                                <h4>Consulter l'historique</h4>
                                <p class="doc-text">
                                    Le tableau principal affiche toutes les désinfections enregistrées avec les informations
                                    clés : véhicule, date, type, protocole, produit et l'agent qui a effectué l'opération.
                                    Utilisez la barre de recherche pour filtrer les résultats par véhicule ou par date.
                                </p>
                            </div>
                        </section>

                        <div class="version-info">
                            <p>AmbuGaz v1.0 - Documentation mise à jour le {{ date('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection