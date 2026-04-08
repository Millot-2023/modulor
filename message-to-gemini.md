# 📁 PROTOCOLE_MODULOR.md
> **Statut :** RÉFÉRENCE ABSOLUE / SQUELETTE
> **Date de l'Audit :** Avril 2026

## 1. LA LOI DU SQUELETTE (LA DICTATURE)
Le projet MODULOR est une infrastructure, pas un site web. La structure prime sur l'esthétique.

- **Souveraineté du DOM :** L'ordre des balises dans `index.php` doit dicter l'ordre à l'écran. 
- **Interdiction des Styles Inline :** Aucun attribut `style=""` de positionnement (`grid`, `span`, `margin`) n'est toléré dans l'index.
- **Neutralité des Containers :** La classe `.modulor-card` est une enveloppe vide. Elle n'a pas de conscience de sa taille ou de sa position.
- **Épure W3C :** Le code doit être le plus "pauvre" possible pour garantir une mécanique JS/PHP sans frottement.

## 2. LA ZONE D'ÉCHANGE (LA DÉMOCRATIE)
C'est ici que l'IA intervient sous surveillance étroite du Patron.

- **Isolation Totale :** Un bloc (ex: `w-notes`) possède son propre dossier, son PHP, son SCSS et son JS. Rien ne doit déborder sur le voisin.
- **Zéro Initiative :** L'IA ne propose aucune "amélioration" ergonomique ou design sans demande explicite.
- **Audit Systématique :** Chaque modification de la charte graphique ou de la structure doit passer par une autorisation.

## 3. ÉTAT DES LIEUX (BANCALE)
- [X] Problème d'inversion des DIV identifié (conflit entre Grid CSS et Ordre DOM).
- [ ] Audit du `tree /f` (En attente).
- [ ] Nettoyage du `main.scss` (En attente).
- [ ] Suppression des `span` dans l'index (En attente).

---
*Document établi pour servir de mémoire technique inviolable.*


//http://localhost/modulor/blocks/w-notes/w-notes.php
//http://localhost/modulor/blocks/w-codepen/w-codepen.php
//http://localhost/modulor/blocks/w-lorem/w-lorem.php