-- Supprimer la colonne stats existante
ALTER TABLE players DROP COLUMN IF EXISTS stats;

-- Ajouter les nouvelles colonnes de statistiques
ALTER TABLE players
ADD COLUMN IF NOT EXISTS goals INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS assists INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS matches INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS clean_sheets INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS yellow_cards INT DEFAULT 0,
ADD COLUMN IF NOT EXISTS red_cards INT DEFAULT 0; 