/* 依存関係順のためのDROP（存在時のみ） */
DROP TABLE IF EXISTS `favorites`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `purchase_details`;
DROP TABLE IF EXISTS `purchases`;
DROP TABLE IF EXISTS `creditcards`;

/* ========== purchases（購入ヘッダ） ========== */
CREATE TABLE `purchases` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `customer_id`   INT(11) NOT NULL,
  `purchase_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  -- 運用上よく使う補助カラム（必要なければ削除可）
  `status`        ENUM('pending','paid','shipped','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `total_amount`  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `idx_purchases_customer_date` (`customer_id`,`purchase_date`),
  CONSTRAINT `fk_purchases_customer`
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* ========== purchase_details（購入明細） ========== */
CREATE TABLE `purchase_details` (
  `purchase_id`   INT(11) NOT NULL,
  `product_id`    INT(11) NOT NULL,
  `purchase_count` INT(11) NOT NULL DEFAULT 1,     -- PDFのpurchase_countを数量と解釈
  -- 単価や小計が必要なら以下を有効化
  -- `unit_price`  DECIMAL(10,2) NOT NULL,
  -- `subtotal`    DECIMAL(10,2) GENERATED ALWAYS AS (`purchase_count` * `unit_price`) STORED,
  PRIMARY KEY (`purchase_id`,`product_id`),
  KEY `idx_purchase_details_product` (`product_id`),
  CONSTRAINT `fk_pdetail_purchase`
    FOREIGN KEY (`purchase_id`) REFERENCES `purchases`(`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_pdetail_product`
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* ========== creditcards（クレジットカード） ========== */
CREATE TABLE `creditcards` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `customer_id`   INT(11) NOT NULL,
  `valid_name`    VARCHAR(100) NOT NULL,      -- カード表記名
  `card_number`   VARCHAR(25)  NOT NULL,      -- トークン保管を推奨（実運用では生番号は保存しない）
  `card_brand`    VARCHAR(30)  NOT NULL,      -- Visa/Master/JCB/Amex等
  `valid_month`   TINYINT(2)   NOT NULL,      -- 1-12
  `valid_year`    SMALLINT(4)  NOT NULL,      -- 例: 2028
  `security_code` VARCHAR(4)   NOT NULL,      -- 3-4桁（実運用では保存しないのが原則）
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_creditcards_customer` (`customer_id`),
  UNIQUE KEY `uk_creditcards_number_customer` (`customer_id`,`card_number`),
  CONSTRAINT `fk_creditcards_customer`
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* ========== payments（支払い） ========== */
CREATE TABLE `payments` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `purchase_id`   INT(11) NOT NULL,
  `creditcard_id` INT(11) DEFAULT NULL,       -- カード以外の決済に備えてNULL許容
  `amount`        DECIMAL(10,2) NOT NULL,
  `status`        ENUM('authorized','captured','failed','cancelled','refunded') NOT NULL DEFAULT 'authorized',
  `paid_at`       DATETIME DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_payments_purchase` (`purchase_id`),
  KEY `idx_payments_creditcard` (`creditcard_id`),
  CONSTRAINT `fk_payments_purchase`
    FOREIGN KEY (`purchase_id`) REFERENCES `purchases`(`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_payments_creditcard`
    FOREIGN KEY (`creditcard_id`) REFERENCES `creditcards`(`id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

/* ========== favorites（お気に入り） ========== */
CREATE TABLE `favorites` (
  `customer_id` INT(11) NOT NULL,
  `product_id`  INT(11) NOT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`customer_id`,`product_id`),
  KEY `idx_favorites_product` (`product_id`),
  CONSTRAINT `fk_favorites_customer`
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_favorites_product`
    FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
