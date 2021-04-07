<?php
class ControllerExtensionModuleMaterialsNik extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/materials_nik');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');
		$this->load->model('extension/module/materials_nik');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('materials_nik', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$this->getList();
	}

    public function addCategory() {
        $this->load->language('extension/module/materials_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/materials_nik');
        $this->load->model('setting/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCategoryForm()) {
            $materials_category_id = $this->model_extension_module_materials_nik->addMaterialsCategory($this->request->post);
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
            $module_info['materials_category_id'] = $materials_category_id;

            $this->model_setting_module->editModule($this->request->get['module_id'], $module_info);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true));
        }

        $this->getFormCategory();
    }

    public function editCategory() {
        $this->load->language('extension/module/materials_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/materials_nik');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCategoryForm()) {
            $this->model_extension_module_materials_nik->editMaterialsCategory($this->request->get['materials_category_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true));
        }

        $this->getFormCategory();
    }

    public function deleteCategory() {
        $this->load->language('extension/module/materials_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/materials_nik');
        $this->load->model('setting/module');

        if (isset($this->request->get['materials_category_id']) && $this->validateDelete()) {
            $this->model_extension_module_materials_nik->deleteMaterialsCategory($this->request->get['materials_category_id']);

            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
            unset($module_info['materials_category_id']);

            $this->model_setting_module->editModule($this->request->get['module_id'], $module_info);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true));
        }

        $this->getList();
    }

    public function addMaterial() {
        $this->load->language('extension/module/materials_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/materials_nik');
        $this->load->model('setting/module');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCategoryForm()) {
            $materials_category_id = $this->model_extension_module_materials_nik->addMaterialsCategory($this->request->post);
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
            $module_info['materials_category_id'] = $materials_category_id;

            $this->model_setting_module->editModule($this->request->get['module_id'], $module_info);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true));
        }

        $this->getFormMaterial();
    }

    public function editMaterial() {
        $this->load->language('extension/module/materials_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/materials_nik');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCategoryForm()) {
            $this->model_extension_module_materials_nik->editMaterialsCategory($this->request->get['materials_category_id'], $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true));
        }

        $this->getFormMaterial();
    }

    public function deleteMaterial() {
        $this->load->language('extension/module/materials_nik');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('extension/module/materials_nik');
        $this->load->model('setting/module');

        if (isset($this->request->get['materials_category_id']) && $this->validateDelete()) {
            $this->model_extension_module_materials_nik->deleteMaterialsCategory($this->request->get['materials_category_id']);

            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
            unset($module_info['materials_category_id']);

            $this->model_setting_module->editModule($this->request->get['module_id'], $module_info);

            $this->session->data['success'] = $this->language->get('text_success');

            $url = '';

            if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
            }

            if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
            }

            if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
            }

            $this->response->redirect($this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true));
        }

        $this->getList();
    }

	protected function getList() {
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'mcd.title';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }

        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = '';
        }

        if (isset($this->error['width'])) {
            $data['error_width'] = $this->error['width'];
        } else {
            $data['error_width'] = '';
        }

        if (isset($this->error['height'])) {
            $data['error_height'] = $this->error['height'];
        } else {
            $data['error_height'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        if (!isset($this->request->get['module_id'])) {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'], true)
            );
        } else {
            $data['breadcrumbs'][] = array(
                'text' => $this->language->get('heading_title'),
                'href' => $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
            );
        }

        if (!isset($this->request->get['module_id'])) {
            $data['action'] = $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
        }

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
        }

        if (isset($this->request->get['module_id']) && empty($module_info['materials_category_id'])) {
            $data['addCategory'] = $this->url->link('extension/module/materials_nik/addCategory', 'user_token=' . $this->session->data['user_token'] .  '&module_id=' . $this->request->get['module_id'], true);
        }

        if (isset($this->request->get['module_id']) && !empty($module_info['materials_category_id'])) {
            $data['addMaterial'] = $this->url->link('extension/module/materials_nik/addMaterial', 'user_token=' . $this->session->data['user_token'] .  '&module_id=' . $this->request->get['module_id'], true);
        }

        $data['module_id'] = isset($this->request->get['module_id']) ? $this->request->get['module_id'] : '';

        if (isset($module_info) && !empty($module_info['materials_category_id'])) {
            $data['materials_categories'] = array();

            $filter_data = array(
                'sort'  => $sort,
                'order' => $order,
                'start' => ($page - 1) * $this->config->get('config_limit_admin'),
                'limit' => $this->config->get('config_limit_admin'),
                'materials_category_id' => $module_info['materials_category_id']
            );

            $results = $this->model_extension_module_materials_nik->getMaterialsCategories($filter_data);

            foreach ($results as $result) {
                $data['materials_categories'][] = array(
                    'materials_category_id' => $result['materials_category_id'],
                    'title'                 => $result['title'],
                    'sort_order'            => $result['sort_order'],
                    'edit'                  => $this->url->link('extension/module/materials_nik/editCategory', 'user_token=' . $this->session->data['user_token'] . '&materials_category_id=' . $result['materials_category_id'] . '&module_id=' . $this->request->get['module_id'], true),
                    'delete'                => $this->url->link('extension/module/materials_nik/deleteCategory', 'user_token=' . $this->session->data['user_token'] . '&materials_category_id=' . $result['materials_category_id'] . '&module_id=' . $this->request->get['module_id'], true)
                );
            }
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->post['name'])) {
            $data['name'] = $this->request->post['name'];
        } elseif (!empty($module_info)) {
            $data['name'] = $module_info['name'];
        } else {
            $data['name'] = '';
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($module_info)) {
            $data['status'] = $module_info['status'];
        } else {
            $data['status'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/materials_nik', $data));
    }

    protected function getFormCategory() {
        $data['text_form'] = !isset($this->request->get['materials_category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['title'])) {
            $data['error_title'] = $this->error['title'];
        } else {
            $data['error_title'] = array();
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['materials_category_id'])) {
            if (isset($this->request->get['module_id'])) {
                $data['action'] = $this->url->link('extension/module/materials_nik/addCategory', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true);
            } else {
                $data['action'] = $this->url->link('extension/module/materials_nik/addCategory', 'user_token=' . $this->session->data['user_token'] . $url, true);
            }
        } else {
            if (isset($this->request->get['module_id'])) {
                $data['action'] = $this->url->link('extension/module/materials_nik/editCategory', 'user_token=' . $this->session->data['user_token'] . '&materials_category_id=' . $this->request->get['materials_category_id'] . '&module_id=' . $this->request->get['module_id'] . $url, true);
            } else {
                $data['action'] = $this->url->link('extension/module/materials_nik/editCategory', 'user_token=' . $this->session->data['user_token'] . '&materials_category_id=' . $this->request->get['materials_category_id'] . $url, true);
            }
        }

        if (isset($this->request->get['module_id'])) {
            $data['cancel'] = $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true);
        } else {
            $data['cancel'] = $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . $url, true);
        }


        if (isset($this->request->get['materials_category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $information_info = $this->model_extension_module_materials_nik->getMaterialsCategory($this->request->get['materials_category_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['materials_categories_description'])) {
            $data['materials_categories_description'] = $this->request->post['materials_categories_description'];
        } elseif (isset($this->request->get['materials_category_id'])) {
            $data['materials_categories_description'] = $this->model_extension_module_materials_nik->getMaterialsCategoriesDescriptions($this->request->get['materials_category_id']);
        } else {
            $data['materials_categories_description'] = array();
        }

        $this->load->model('setting/store');

        $data['stores'] = array();

        $data['stores'][] = array(
            'store_id' => 0,
            'name'     => $this->language->get('text_default')
        );

        $stores = $this->model_setting_store->getStores();

        foreach ($stores as $store) {
            $data['stores'][] = array(
                'store_id' => $store['store_id'],
                'name'     => $store['name']
            );
        }

        if (isset($this->request->post['materials_categories_store'])) {
            $data['materials_categories_store'] = $this->request->post['materials_categories_store'];
        } elseif (isset($this->request->get['materials_category_id'])) {
            $data['materials_categories_store'] = $this->model_extension_module_materials_nik->getMaterialsCategoryStores($this->request->get['materials_category_id']);
        } else {
            $data['materials_categories_store'] = array(0);
        }

        if (isset($this->request->post['bottom'])) {
            $data['bottom'] = $this->request->post['bottom'];
        } elseif (!empty($information_info)) {
            $data['bottom'] = $information_info['bottom'];
        } else {
            $data['bottom'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($information_info)) {
            $data['status'] = $information_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($information_info)) {
            $data['sort_order'] = $information_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        if (isset($this->request->post['materials_categories_seo_url'])) {
            $data['materials_categories_seo_url'] = $this->request->post['materials_categories_seo_url'];
        } elseif (isset($this->request->get['materials_category_id'])) {
            $data['materials_categories_seo_url'] = $this->model_extension_module_materials_nik->getMaterialsCategorySeoUrls($this->request->get['materials_category_id']);
        } else {
            $data['materials_categories_seo_url'] = array();
        }

        if (isset($this->request->post['materials_categories_layout'])) {
            $data['information_layout'] = $this->request->post['materials_categories_layout'];
        } elseif (isset($this->request->get['materials_category_id'])) {
            $data['materials_categories_layout'] = $this->model_extension_module_materials_nik->getMaterialsCategoryLayouts($this->request->get['materials_category_id']);
        } else {
            $data['materials_categories_layout'] = array();
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/materials_form_category_nik', $data));
    }

    protected function getFormMaterial() {
        $data['text_form'] = !isset($this->request->get['materials_material_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['title'])) {
            $data['error_title'] = $this->error['title'];
        } else {
            $data['error_title'] = array();
        }

        if (isset($this->error['description'])) {
            $data['error_description'] = $this->error['description'];
        } else {
            $data['error_description'] = array();
        }

        if (isset($this->error['meta_title'])) {
            $data['error_meta_title'] = $this->error['meta_title'];
        } else {
            $data['error_meta_title'] = array();
        }

        if (isset($this->error['keyword'])) {
            $data['error_keyword'] = $this->error['keyword'];
        } else {
            $data['error_keyword'] = '';
        }

        $url = '';

        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . $url, true)
        );

        if (!isset($this->request->get['materials_material_id'])) {
            if (isset($this->request->get['module_id'])) {
                $data['action'] = $this->url->link('extension/module/materials_nik/addMaterial', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true);
            } else {
                $data['action'] = $this->url->link('extension/module/materials_nik/addMaterial', 'user_token=' . $this->session->data['user_token'] . $url, true);
            }
        } else {
            if (isset($this->request->get['module_id'])) {
                $data['action'] = $this->url->link('extension/module/materials_nik/editMaterial', 'user_token=' . $this->session->data['user_token'] . '&materials_material_id=' . $this->request->get['materials_material_id'] . '&module_id=' . $this->request->get['module_id'] . $url, true);
            } else {
                $data['action'] = $this->url->link('extension/module/materials_nik/editMaterial', 'user_token=' . $this->session->data['user_token'] . '&materials_material_id=' . $this->request->get['materials_material_id'] . $url, true);
            }
        }

        if (isset($this->request->get['module_id'])) {
            $data['cancel'] = $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'] . $url, true);
        } else {
            $data['cancel'] = $this->url->link('extension/module/materials_nik', 'user_token=' . $this->session->data['user_token'] . $url, true);
        }

        $data['user_token'] = $this->session->data['user_token'];

        $this->load->model('localisation/language');

        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['materials_categories_description'])) {
            $data['materials_categories_description'] = $this->request->post['materials_categories_description'];
        } elseif (isset($this->request->get['materials_category_id'])) {
            $data['materials_categories_description'] = $this->model_extension_module_materials_nik->getMaterialsCategoriesDescriptions($this->request->get['materials_category_id']);
        } else {
            $data['materials_categories_description'] = array();
        }

        $this->load->model('setting/store');

        $data['stores'] = array();

        $data['stores'][] = array(
            'store_id' => 0,
            'name'     => $this->language->get('text_default')
        );

        $stores = $this->model_setting_store->getStores();

        foreach ($stores as $store) {
            $data['stores'][] = array(
                'store_id' => $store['store_id'],
                'name'     => $store['name']
            );
        }

        if (isset($this->request->post['materials_categories_store'])) {
            $data['materials_categories_store'] = $this->request->post['materials_categories_store'];
        } elseif (isset($this->request->get['materials_category_id'])) {
            $data['materials_categories_store'] = $this->model_extension_module_materials_nik->getMaterialsCategoryStores($this->request->get['materials_category_id']);
        } else {
            $data['materials_categories_store'] = array(0);
        }

        if (isset($this->request->post['bottom'])) {
            $data['bottom'] = $this->request->post['bottom'];
        } elseif (!empty($information_info)) {
            $data['bottom'] = $information_info['bottom'];
        } else {
            $data['bottom'] = 0;
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($information_info)) {
            $data['status'] = $information_info['status'];
        } else {
            $data['status'] = true;
        }

        if (isset($this->request->post['sort_order'])) {
            $data['sort_order'] = $this->request->post['sort_order'];
        } elseif (!empty($information_info)) {
            $data['sort_order'] = $information_info['sort_order'];
        } else {
            $data['sort_order'] = '';
        }

        if (isset($this->request->post['materials_categories_seo_url'])) {
            $data['materials_categories_seo_url'] = $this->request->post['materials_categories_seo_url'];
        } elseif (isset($this->request->get['materials_category_id'])) {
            $data['materials_categories_seo_url'] = $this->model_extension_module_materials_nik->getMaterialsCategorySeoUrls($this->request->get['materials_category_id']);
        } else {
            $data['materials_categories_seo_url'] = array();
        }

        if (isset($this->request->post['materials_categories_layout'])) {
            $data['information_layout'] = $this->request->post['materials_categories_layout'];
        } elseif (isset($this->request->get['materials_category_id'])) {
            $data['materials_categories_layout'] = $this->model_extension_module_materials_nik->getMaterialsCategoryLayouts($this->request->get['materials_category_id']);
        } else {
            $data['materials_categories_layout'] = array();
        }

        $this->load->model('design/layout');

        $data['layouts'] = $this->model_design_layout->getLayouts();

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/materials_form_material_nik', $data));
    }

    public function install() {
        if ($this->user->hasPermission('modify', 'extension/module/materials_nik')) {
            $this->load->model('extension/module/materials_nik');

            $this->model_extension_module_materials_nik->install();
        }
    }

    public function uninstall() {
        if ($this->user->hasPermission('modify', 'extension/module/materials_nik')) {
            $this->load->model('extension/module/materials_nik');

            $this->model_extension_module_materials_nik->uninstall();
        }
    }

    protected function validateCategoryForm() {
        if (!$this->user->hasPermission('modify', 'extension/module/materials_nik')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['materials_categories_description'] as $language_id => $value) {
            if ((utf8_strlen($value['title']) < 1) || (utf8_strlen($value['title']) > 64)) {
                $this->error['title'][$language_id] = $this->language->get('error_title');
            }

            if (utf8_strlen($value['description']) < 3) {
                $this->error['description'][$language_id] = $this->language->get('error_description');
            }

            if ((utf8_strlen($value['meta_title']) < 1) || (utf8_strlen($value['meta_title']) > 255)) {
                $this->error['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }

        if ($this->request->post['materials_categories_seo_url']) {
            $this->load->model('design/seo_url');

            foreach ($this->request->post['materials_categories_seo_url'] as $store_id => $language) {
                foreach ($language as $language_id => $keyword) {
                    if (!empty($keyword)) {
                        if (count(array_keys($language, $keyword)) > 1) {
                            $this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
                        }

                        $seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);

                        foreach ($seo_urls as $seo_url) {
                            if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['materials_category_id']) || ($seo_url['query'] != 'materials_category_id=' . $this->request->get['materials_category_id']))) {
                                $this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');
                            }
                        }
                    }
                }
            }
        }

        if ($this->error && !isset($this->error['warning'])) {
            $this->error['warning'] = $this->language->get('error_warning');
        }

        return !$this->error;
    }

    protected function validateDelete() {
        if (!$this->user->hasPermission('modify', 'extension/module/materials_nik')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/materials_nik')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}
}
