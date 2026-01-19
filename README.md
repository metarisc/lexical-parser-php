# Lexical to ODT Converter

Un convertisseur PHP qui transforme du contenu au format [Lexical](https://lexical.dev/) (JSON AST) en documents OpenDocument Text (ODT).

## Description

Ce projet permet de convertir des structures de données JSON générées par l'éditeur Lexical en documents ODT professionnels. Il parse l'arbre syntaxique abstrait (AST) de Lexical et génère le XML ODT correspondant en respectant les spécifications OpenDocument Format (ODF).

### Fonctionnalités

- **Paragraphes** avec formatage complet
- **Titres** (headings) de niveau 1 à 6
- **Formatage de texte** : gras, italique, souligné, code, exposant, indice
- **Couleurs** : couleur de texte et fond (surbrillance)
- **Liens hypertextes**
- **Listes** ordonnées et non ordonnées (avec imbrication)
- **Tableaux** avec support des cellules fusionnées
- **Images** avec dimensions
- **Sauts de ligne** et **sauts de page**
- **Alignement de texte** (gauche, centre, droite, justifié)

### Composants

#### 1. **Converter** (`src/Converter/`)
- Point d'entrée principal de l'application
- Initialise le parser et le renderer approprié
- Orchestre le processus de conversion

#### 2. **Parser** (`src/Parser/`)
- **LexicalParser** : Parse le JSON Lexical et construit un arbre de nodes PHP
- Transforme la structure JSON en objets PHP typés et structurés

#### 3. **Nodes** (`src/Nodes/`)
Représentation objet de l'AST Lexical :
- `RootNode` : Nœud racine du document
- `ParagraphNode` : Paragraphe de texte
- `HeadingNode` : Titre (h1 à h6)
- `TextNode` : Fragment de texte avec formatage
- `LinkNode` : Lien hypertexte
- `ListNode` / `ListItemNode` : Listes et éléments de liste
- `TableNode` / `TableRowNode` / `TableCellNode` : Tableaux
- `ImageNode` : Images
- `LineBreakNode` : Saut de ligne
- `PageBreakNode` : Saut de page

#### 4. **Renderer** (`src/Renderer/`)
- **OdtRenderer** : Implémente le pattern Visitor
- Traverse l'arbre de nodes et génère le XML ODT correspondant
- Gère la création des styles automatiques ODT
- Produit un XML conforme aux spécifications OpenDocument

#### 5. **Odt** (`src/Odt.php`)
- Classe utilitaire pour manipuler les fichiers ODT (extends ZipFile)
- Gère l'assemblage du document final
- Intègre les styles automatiques et le contenu XML

## Structure du code

```
src/
├── Odt.php                      # Classe utilitaire ODT
├── Converter/
│   ├── Converter.php            # Convertisseur principal
│   └── ConverterInterface.php
├── Parser/
│   ├── LexicalParser.php        # Parser JSON → Nodes
│   └── ParserInterface.php
├── Renderer/
│   ├── OdtRenderer.php          # Visitor Nodes → XML ODT
│   └── RendererInterface.php
└── Nodes/
    ├── NodeInterface.php        # Interface commune
    ├── RootNode.php
    ├── LineBreakNode.php
    ├── PageBreakNode.php
    ├── Element/                 # Nœuds de type élément
    │   ├── ParagraphNode.php
    │   ├── HeadingNode.php
    │   ├── ImageNode.php
    │   ├── Text/
    │   │   ├── TextNode.php
    │   │   └── LinkNode.php
    │   ├── List/
    │   │   ├── ListNode.php
    │   │   └── ListItemNode.php
    │   └── Table/
    │       ├── TableNode.php
    │       ├── TableRowNode.php
    │       └── TableCellNode.php
    └── Styles/                  # Classes de gestion des styles
        ├── Style.php
        └── TextFormat.php
```