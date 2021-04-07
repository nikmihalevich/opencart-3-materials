<?php
class ModelExtensionModuleMaterialsNik extends Model {
    public function install() {
        $this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "materials_categories` (
			`materials_category_id` INT(11) NOT NULL AUTO_INCREMENT,
			`bottom` INT(1) NOT NULL DEFAULT 0,
			`sort_order` INT(3) NOT NULL DEFAULT 0,
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
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_categories`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_categories_description`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_categories_to_layout`");
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "materials_categories_to_store`");
    }

    public function addMaterialsCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "materials_categories SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "'");

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
        $this->db->query("UPDATE " . DB_PREFIX . "materials_categories SET sort_order = '" . (int)$data['sort_order'] . "', bottom = '" . (isset($data['bottom']) ? (int)$data['bottom'] : 0) . "', status = '" . (int)$data['status'] . "' WHERE materials_category_id = '" . (int)$materials_category_id . "'");

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
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "information");

        return $query->row['total'];
    }

    public function getTotalMaterialsCategoriesByLayoutId($layout_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "materials_categories_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

        return $query->row['total'];
    }
}