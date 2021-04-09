<?php
class ModelExtensionModuleMaterialsNik extends Model {
    public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "materials_categories` (
			`materials_category_id` INT(11) NOT NULL AUTO_INCREMENT,
			`display_type` VARCHAR(64) NOT NULL,
			`status` TINYINT(1) NOT NULL DEFAULT 1,
			PRIMARY KEY (`materials_category_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "materials_categories_description` (
            `materials_category_id` INT(11) NOT NULL AUTO_INCREMENT,
            `language_id` INT(11) NOT NULL,
            `title` VARCHAR(64) NOT NULL,
            `description` mediumtext NOT NULL,
            `meta_title` VARCHAR(255) NOT NULL,
            `meta_description` VARCHAR(255) NOT NULL,
            `meta_keyword` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`materials_category_id`, `language_id`)
		) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "materials_categories_to_layout` (
            `materials_category_id` INT(11) NOT NULL AUTO_INCREMENT,
            `store_id` INT(11) NOT NULL,
            `layout_id` INT(11) NOT NULL,
            PRIMARY KEY (`materials_category_id`, `store_id`)
		) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "materials_categories_to_store` (
            `materials_category_id` INT(11) NOT NULL AUTO_INCREMENT,
            `store_id` INT(11) NOT NULL,
            PRIMARY KEY (`materials_category_id`, `store_id`)
		) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");

        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "materials` (
			`material_id` INT(11) NOT NULL AUTO_INCREMENT,
			`materials_category_id` INT(11) NOT NULL,
			`image` VARCHAR(255) NOT NULL,
			`status` TINYINT(1) NOT NULL DEFAULT 1,
			`sort_order` INT(3) NOT NULL DEFAULT 0,
			PRIMARY KEY (`material_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci");
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "materials_description` (
            `material_id` INT(11) NOT NULL AUTO_INCREMENT,
            `language_id` INT(11) NOT NULL,
            `title` VARCHAR(64) NOT NULL,
            `description` mediumtext NOT NULL,
            `meta_title` VARCHAR(255) NOT NULL,
            `meta_description` VARCHAR(255) NOT NULL,
            `meta_keyword` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`material_id`, `language_id`)
		) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "materials_to_layout` (
            `material_id` INT(11) NOT NULL AUTO_INCREMENT,
            `store_id` INT(11) NOT NULL,
            `layout_id` INT(11) NOT NULL,
            PRIMARY KEY (`material_id`, `store_id`)
		) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "materials_to_store` (
            `material_id` INT(11) NOT NULL AUTO_INCREMENT,
            `store_id` INT(11) NOT NULL,
            PRIMARY KEY (`material_id`, `store_id`)
		) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_categories`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_categories_description`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_categories_to_layout`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_categories_to_store`");

        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_description`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_to_layout`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_to_store`");
    }

    public function addMaterialsCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "materials_categories SET display_type = '" . $this->db->escape($data['display_type']) . "', status = '" . (int)$data['status'] . "'");

        $materials_category_id = $this->db->getLastId();

        foreach ($data['materials_categories_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "materials_categories_description SET materials_category_id = '" . (int)$materials_category_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['materials_categories_store'])) {
            foreach ($data['materials_categories_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "materials_categories_to_store SET materials_category_id = '" . (int)$materials_category_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        // SEO URL
        if (isset($data['materials_categories_seo_url'])) {
            foreach ($data['materials_categories_seo_url'] as $store_id => $language) {
                foreach ($language as $language_id => $keyword) {
                    if (!empty($keyword)) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'materials_category_id=" . (int)$materials_category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
                    }
                }
            }
        }

        if (isset($data['materials_categories_layout'])) {
            foreach ($data['materials_categories_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "materials_categories_to_layout SET materials_category_id = '" . (int)$materials_category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        $this->cache->delete('materials_categories');

        return $materials_category_id;
    }

    public function editMaterialsCategory($materials_category_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "materials_categories SET display_type = '" . $this->db->escape($data['display_type']) . "', status = '" . (int)$data['status'] . "' WHERE materials_category_id = '" . (int)$materials_category_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "materials_categories_description WHERE materials_category_id = '" . (int)$materials_category_id . "'");

        foreach ($data['materials_categories_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "materials_categories_description SET materials_category_id = '" . (int)$materials_category_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "materials_categories_to_store WHERE materials_category_id = '" . (int)$materials_category_id . "'");

        if (isset($data['materials_categories_store'])) {
            foreach ($data['materials_categories_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "materials_categories_to_store SET materials_category_id = '" . (int)$materials_category_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'materials_category_id=" . (int)$materials_category_id . "'");

        if (isset($data['materials_categories_seo_url'])) {
            foreach ($data['materials_categories_seo_url'] as $store_id => $language) {
                foreach ($language as $language_id => $keyword) {
                    if (trim($keyword)) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'materials_category_id=" . (int)$materials_category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
                    }
                }
            }
        }

        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials_categories_to_layout` WHERE materials_category_id = '" . (int)$materials_category_id . "'");

        if (isset($data['materials_categories_layout'])) {
            foreach ($data['materials_categories_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "materials_categories_to_layout` SET materials_category_id = '" . (int)$materials_category_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        $this->cache->delete('materials_categories');
    }

    public function deleteMaterialsCategory($materials_category_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials_categories` WHERE materials_category_id = '" . (int)$materials_category_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials_categories_description` WHERE materials_category_id = '" . (int)$materials_category_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials_categories_to_store` WHERE materials_category_id = '" . (int)$materials_category_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials_categories_to_layout` WHERE materials_category_id = '" . (int)$materials_category_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'materials_category_id=" . (int)$materials_category_id . "'");

        $this->cache->delete('materials_categories');
    }

    public function getMaterialsCategory($materials_category_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "materials_categories WHERE materials_category_id = '" . (int)$materials_category_id . "'");

        return $query->row;
    }

    public function getMaterialsCategories($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "materials_categories mc LEFT JOIN " . DB_PREFIX . "materials_categories_description mcd ON (mc.materials_category_id = mcd.materials_category_id) WHERE mcd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            $sort_data = array(
                'mcd.title',
                'mc.sort_order'
            );

            if (isset($data['materials_category_id'])) {
                $sql .= " AND mc.materials_category_id = '" . (int)$data['materials_category_id'] . "'";
            }

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY mcd.title";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }


            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $materials_category_data = $this->cache->get('materials_categories.' . (int)$this->config->get('config_language_id'));

            if (!$materials_category_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "materials_categories mc LEFT JOIN " . DB_PREFIX . "materials_categories_description mcd ON (mc.materials_category_id = mcd.materials_category_id) WHERE mcd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY mcd.title");

                $materials_category_data = $query->rows;

                $this->cache->set('materials_categories.' . (int)$this->config->get('config_language_id'), $materials_category_data);
            }

            return $materials_category_data;
        }
    }

    public function getMaterialsCategoriesDescriptions($materials_category_id) {
        $materials_category_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "materials_categories_description WHERE materials_category_id = '" . (int)$materials_category_id . "'");

        foreach ($query->rows as $result) {
            $materials_category_description_data[$result['language_id']] = array(
                'title'            => $result['title'],
                'description'      => $result['description'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword']
            );
        }

        return $materials_category_description_data;
    }

    public function getMaterialsCategoryStores($materials_category_id) {
        $materials_category_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "materials_categories_to_store WHERE materials_category_id = '" . (int)$materials_category_id . "'");

        foreach ($query->rows as $result) {
            $materials_category_store_data[] = $result['store_id'];
        }

        return $materials_category_store_data;
    }

    public function getMaterialsCategorySeoUrls($materials_category_id) {
        $materials_category_seo_url_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'materials_category_id=" . (int)$materials_category_id . "'");

        foreach ($query->rows as $result) {
            $materials_category_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
        }

        return $materials_category_seo_url_data;
    }

    public function getMaterialsCategoryLayouts($materials_category_id) {
        $materials_category_layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "materials_categories_to_layout WHERE materials_category_id = '" . (int)$materials_category_id . "'");

        foreach ($query->rows as $result) {
            $materials_category_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $materials_category_layout_data;
    }

    public function getTotalMaterialsCategories() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "materials_categories");

        return $query->row['total'];
    }

    public function getTotalMaterialsCategoriesByLayoutId($layout_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "materials_categories_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->row['total'];
    }


    // Material Functions

    public function addMaterial($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "materials SET materials_category_id = '" . (int)$data['materials_category_id'] . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "'");

        $material_id = $this->db->getLastId();

        foreach ($data['materials_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "materials_description SET material_id = '" . (int)$material_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['materials_store'])) {
            foreach ($data['materials_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "materials_to_store SET material_id = '" . (int)$material_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        // SEO URL
        if (isset($data['materials_seo_url'])) {
            foreach ($data['materials_seo_url'] as $store_id => $language) {
                foreach ($language as $language_id => $keyword) {
                    if (!empty($keyword)) {
                        $this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'material_id=" . (int)$material_id . "', keyword = '" . $this->db->escape($keyword) . "'");
                    }
                }
            }
        }

        if (isset($data['materials_layout'])) {
            foreach ($data['materials_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "materials_to_layout SET material_id = '" . (int)$material_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        $this->cache->delete('materials');

        return $material_id;
    }

    public function editMaterial($material_id, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "materials SET image = '" . $this->db->escape($data['image']) . "', sort_order = '" . (int)$data['sort_order'] . "', status = '" . (int)$data['status'] . "' WHERE material_id = '" . (int)$material_id . "'");

        $this->db->query("DELETE FROM " . DB_PREFIX . "materials_description WHERE material_id = '" . (int)$material_id . "'");

        foreach ($data['materials_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "materials_description SET material_id = '" . (int)$material_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "materials_to_store WHERE material_id = '" . (int)$material_id . "'");

        if (isset($data['materials_store'])) {
            foreach ($data['materials_store'] as $store_id) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "materials_to_store SET material_id = '" . (int)$material_id . "', store_id = '" . (int)$store_id . "'");
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "seo_url WHERE query = 'material_id=" . (int)$material_id . "'");

        if (isset($data['materials_seo_url'])) {
            foreach ($data['materials_seo_url'] as $store_id => $language) {
                foreach ($language as $language_id => $keyword) {
                    if (trim($keyword)) {
                        $this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET store_id = '" . (int)$store_id . "', language_id = '" . (int)$language_id . "', query = 'material_id=" . (int)$material_id . "', keyword = '" . $this->db->escape($keyword) . "'");
                    }
                }
            }
        }

        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials_to_layout` WHERE material_id = '" . (int)$material_id . "'");

        if (isset($data['materials_layout'])) {
            foreach ($data['materials_layout'] as $store_id => $layout_id) {
                $this->db->query("INSERT INTO `" . DB_PREFIX . "materials_to_layout` SET material_id = '" . (int)$material_id . "', store_id = '" . (int)$store_id . "', layout_id = '" . (int)$layout_id . "'");
            }
        }

        $this->cache->delete('materials');
    }

    public function deleteMaterial($material_id) {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials` WHERE material_id = '" . (int)$material_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials_description` WHERE material_id = '" . (int)$material_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials_to_store` WHERE material_id = '" . (int)$material_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "materials_to_layout` WHERE material_id = '" . (int)$material_id . "'");
        $this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE query = 'material_id=" . (int)$material_id . "'");

        $this->cache->delete('materials');
    }

    public function getMaterial($material_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "materials WHERE material_id = '" . (int)$material_id . "'");

        return $query->row;
    }

    public function getMaterials($data = array()) {
        if ($data) {
            $sql = "SELECT * FROM " . DB_PREFIX . "materials m LEFT JOIN " . DB_PREFIX . "materials_description md ON (m.material_id = md.material_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "'";

            $sort_data = array(
                'md.title',
                'm.sort_order'
            );

            if (isset($data['materials_category_id'])) {
                $sql .= " AND m.materials_category_id = '" . (int)$data['materials_category_id'] . "'";
            }

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY " . $data['sort'];
            } else {
                $sql .= " ORDER BY md.title";
            }

            if (isset($data['order']) && ($data['order'] == 'DESC')) {
                $sql .= " DESC";
            } else {
                $sql .= " ASC";
            }


            if (isset($data['start']) || isset($data['limit'])) {
                if ($data['start'] < 0) {
                    $data['start'] = 0;
                }

                if ($data['limit'] < 1) {
                    $data['limit'] = 20;
                }

                $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
            }

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $material_data = $this->cache->get('materials.' . (int)$this->config->get('config_language_id'));

            if (!$material_data) {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "materials m LEFT JOIN " . DB_PREFIX . "materials_description md ON (m.material_id = md.material_id) WHERE md.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY md.title");

                $material_data = $query->rows;

                $this->cache->set('materials.' . (int)$this->config->get('config_language_id'), $material_data);
            }

            return $material_data;
        }
    }

    public function getMaterialDescriptions($material_id) {
        $material_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "materials_description WHERE material_id = '" . (int)$material_id . "'");

        foreach ($query->rows as $result) {
            $material_description_data[$result['language_id']] = array(
                'title'            => $result['title'],
                'description'      => $result['description'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword']
            );
        }

        return $material_description_data;
    }

    public function getMaterialStores($material_id) {
        $material_store_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "materials_to_store WHERE material_id = '" . (int)$material_id . "'");

        foreach ($query->rows as $result) {
            $material_store_data[] = $result['store_id'];
        }

        return $material_store_data;
    }

    public function getMaterialSeoUrls($material_id) {
        $material_seo_url_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'material_id=" . (int)$material_id . "'");

        foreach ($query->rows as $result) {
            $material_seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
        }

        return $material_seo_url_data;
    }

    public function getMaterialLayouts($material_id) {
        $material_layout_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "materials_to_layout WHERE material_id = '" . (int)$material_id . "'");

        foreach ($query->rows as $result) {
            $material_layout_data[$result['store_id']] = $result['layout_id'];
        }

        return $material_layout_data;
    }

    public function getTotalMaterials() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "materials");

        return $query->row['total'];
    }

    public function getTotalMaterialsByLayoutId($layout_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "materials_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->row['total'];
    }
}