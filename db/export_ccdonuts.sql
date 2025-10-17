-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-10-15 02:14:07
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `ccdonuts`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `creditcards`
--

CREATE TABLE `creditcards` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `valid_name` varchar(100) NOT NULL,
  `card_number` varchar(25) NOT NULL,
  `card_brand` varchar(30) NOT NULL,
  `valid_month` tinyint(2) NOT NULL,
  `valid_year` smallint(4) NOT NULL,
  `security_code` varchar(4) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `furigana` varchar(100) NOT NULL,
  `postcode_a` char(3) NOT NULL,
  `postcode_b` char(4) NOT NULL,
  `address` varchar(200) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `customers`
--

INSERT INTO `customers` (`id`, `name`, `furigana`, `postcode_a`, `postcode_b`, `address`, `mail`, `password`) VALUES
(1002, '茸筍', 'キノコタケノコ', '123', '4567', '山', 'kinoko_takenoko@yama.com', '$2y$10$HhAz0ioB9TkPnOqHgcmxZeiLAWQajt7gU1yLtrMt6QD0mhkw9Stuq'),
(1003, 'テスト1', 'テストイチ', '123', '4567', '山', 'test1@yama.com', '$2y$10$rg1mBMieuJGvXbMF4aU8eOaVd60LizwRtcGT9FOx3xE/PpTCfyxCy');

-- --------------------------------------------------------

--
-- テーブルの構造 `favorites`
--

CREATE TABLE `favorites` (
  `customerId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `favorites`
--

INSERT INTO `favorites` (`customerId`, `productId`, `createdAt`) VALUES
(1003, 1, '2025-10-09 14:18:27'),
(1003, 8, '2025-10-09 09:36:16');

-- --------------------------------------------------------

--
-- テーブルの構造 `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `purchaseId` int(11) NOT NULL,
  `creditcardId` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('authorized','captured','failed','cancelled','refunded') NOT NULL DEFAULT 'authorized',
  `paidAt` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `introduction` varchar(1000) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `isNew` int(11) NOT NULL,
  `isSet` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `introduction`, `image`, `isNew`, `isSet`) VALUES
(1, 'CCドーナツ 当店オリジナル（5個入り）', 1500, '当店のオリジナル商品、CCドーナツは、サクサクの食感が特徴のプレーンタイプのドーナツです。素材にこだわり、丁寧に揚げた生地は軽やかでサクッとした食感が楽しめます。一口食べれば、口の中に広がる甘くて香ばしい香りと、口どけの良い食感が感じられます。', 'original.jpg', 0, 0),
(2, 'チョコレートデライト（5個入り）', 1600, 'チョコレートデライトは、濃厚なカカオの風味となめらかな口どけが特徴です。ひとつひとつ丁寧に仕上げたひと口サイズのチョコレートは、口に入れた瞬間に広がる芳醇な香りと上品な甘さをお楽しみいただけます。', 'chocolateDelight.jpg', 0, 0),
(3, 'キャラメルクリーム（5個入り）', 1600, 'キャラメルクリームは、やさしい甘さのキャラメルと、とろけるようなクリームの味わいが楽しめるスイーツです。なめらかな口どけと香ばしい風味が広がり、ひと口ごとに心まで満たされる上品な味わいに仕上げました。', 'caramelCream.jpg', 0, 0),
(4, 'プレーンクラシック（5個入り）', 1500, 'プレーンクラシック（5個入り）は、シンプルだからこそ素材の良さと職人の技が際立つスイーツです。香ばしく焼き上げた生地はふんわり軽やかで、ひと口食べればやさしい甘さと素朴な風味が広がります。毎日でも食べたくなる、当店定番のクラシックな一品です。', 'plainClassic.jpg', 0, 0),
(5, 'サマーシトラス（5個入り）', 1600, 'サマーシトラス（5個入り）は、爽やかな香りと軽やかな甘さが楽しめる限定スイーツです。ふんわり焼き上げた生地にシトラスの風味を閉じ込め、ひと口ごとに広がる清涼感は暑い季節にぴったり。紅茶やアイスコーヒーとの相性も良く、贈り物にもおすすめの爽快な一品です。', 'summerCitrus.jpg', 1, 0),
(6, 'ストロベリークラッシュ（5個入り）', 1800, 'ストロベリークラッシュ（5個入り）は、甘酸っぱい苺の香りとジューシーな果実感が楽しめる華やかなスイーツです。ふんわり焼き上げた生地にストロベリーの風味をぎゅっと閉じ込め、ひと口ごとに広がるフレッシュな味わいが特徴の一品です。', 'strawberryCrush.jpg', 0, 0),
(7, 'フルーツドーナツセット（12個入り）', 3500, '新鮮で豊かなフルーツをたっぷりと使用した贅沢な12個入りセットです。このセットには、季節の最高のフルーツを厳選し、ドーナツに取り入れました。口に入れた瞬間にフルーツの風味と生地のハーモニーが広がります。色鮮やかな見た目も魅力の一つです。', 'fruitDonutAssortment.jpg', 0, 1),
(8, 'フルーツドーナツセット（14個入り）', 4000, 'フルーツドーナツセット（14個入り）は、爽やかな柑橘や甘酸っぱい苺など、多彩なフルーツフレーバーをたっぷり楽しめるボリューム満点のセットです。人数の多い集まりや特別なシーンにもぴったり。華やかで満足感のあるひと箱が、食卓をより楽しく彩ります。', 'fruitDonutSet.jpg', 0, 1),
(9, 'ベストセレクションボックス（4個入り）', 1200, '当店おすすめの人気フレーバーを詰め合わせたベストセレクションボックス（4個入り）は、少量ながらこだわりの味を堪能できる特別なセットです。丁寧に仕上げたドーナツは、贈り物や自分へのご褒美にもぴったり。コンパクトでも満足感のある自慢のセレクションです。', 'bestSelectionBox.jpg', 0, 1),
(10, 'チョコクラッシュボックス（7個入り）', 2400, '濃厚なチョコレートの風味をたっぷり楽しめるチョコクラッシュボックス（7個入り）は、食べ応えのある満足セットです。外はサクッと、中はふんわり仕上げたドーナツは、家族や友人とのシェアやギフトにも最適。チョコ好きに贈る特別なひと箱です。', 'chocolateCrushBox.jpg', 0, 1),
(11, 'クリームボックス（4個入り）', 1400, 'なめらかなクリームの甘さが楽しめるクリームボックス（4個入り）は、気軽に味わえる少量セットです。ふんわり生地と濃厚クリームの絶妙なバランスは、ティータイムやちょっとしたギフトにもぴったり。コンパクトでも満足感のある一品です。', 'creamBox4pcs.jpg', 0, 1),
(12, 'クリームボックス（9個入り）', 2800, 'クリームボックス（9個入り）は、なめらかなクリームの濃厚な味わいをたっぷり楽しめるボリュームセットです。ふんわり軽い生地とコク深いクリームが、ひと口ごとに広がる贅沢なひとときを演出します。大切な方へのギフトにも喜ばれる存在感のあるひと箱です。', 'creamBox9pcs.jpg', 0, 1);

-- --------------------------------------------------------

--
-- テーブルの構造 `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `customerId` int(11) NOT NULL,
  `purchaseDate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','paid','shipped','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `totalAmount` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `purchases`
--

INSERT INTO `purchases` (`id`, `customerId`, `purchaseDate`, `status`, `totalAmount`) VALUES
(1, 1003, '2025-10-09 14:36:10', 'pending', 15200),
(2, 1003, '2025-10-09 14:58:17', 'pending', 16800);

-- --------------------------------------------------------

--
-- テーブルの構造 `purchase_details`
--

CREATE TABLE `purchase_details` (
  `purchaseId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `purchaseCount` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `purchase_details`
--

INSERT INTO `purchase_details` (`purchaseId`, `productId`, `purchaseCount`) VALUES
(1, 2, 5),
(1, 6, 4),
(2, 12, 6);

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `creditcards`
--
ALTER TABLE `creditcards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_creditcards_number_customer` (`customer_id`,`card_number`),
  ADD KEY `idx_creditcards_customer` (`customer_id`);

--
-- テーブルのインデックス `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`);

--
-- テーブルのインデックス `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`customerId`,`productId`),
  ADD KEY `idx_favorites_product` (`productId`);

--
-- テーブルのインデックス `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_payments_purchase` (`purchaseId`),
  ADD KEY `idx_payments_creditcard` (`creditcardId`);

--
-- テーブルのインデックス `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_purchases_customer_date` (`customerId`,`purchaseDate`);

--
-- テーブルのインデックス `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD PRIMARY KEY (`purchaseId`,`productId`),
  ADD KEY `idx_purchase_details_product` (`productId`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `creditcards`
--
ALTER TABLE `creditcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1004;

--
-- テーブルの AUTO_INCREMENT `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- テーブルの AUTO_INCREMENT `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `creditcards`
--
ALTER TABLE `creditcards`
  ADD CONSTRAINT `fk_creditcards_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_customer` FOREIGN KEY (`customerId`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorites_product` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_creditcard` FOREIGN KEY (`creditcardId`) REFERENCES `creditcards` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payments_purchase` FOREIGN KEY (`purchaseId`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- テーブルの制約 `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `fk_purchases_customer` FOREIGN KEY (`customerId`) REFERENCES `customers` (`id`) ON UPDATE CASCADE;

--
-- テーブルの制約 `purchase_details`
--
ALTER TABLE `purchase_details`
  ADD CONSTRAINT `fk_pdetail_product` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pdetail_purchase` FOREIGN KEY (`purchaseId`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
