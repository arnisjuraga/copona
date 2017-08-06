<?php
class ControllerExtensionShippingFlat extends Controller {
    private $error = array();

    public function index() {
        $data = $this->load->language('extension/shipping/flat');

        $this->load->model('localisation/language');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('flat', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['save_continue']) && $this->request->post['save_continue'])
                $this->response->redirect($this->url->link('extension/shipping/flat', 'token=' . $this->session->data['token'] . $url, true));
            else
                $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/shipping/flat', 'token=' . $this->session->data['token'], true)
        );

        $data['action'] = $this->url->link('extension/shipping/flat', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);

        if (isset($this->request->post['flat_cost'])) {
            $data['flat_cost'] = $this->request->post['flat_cost'];
        } else {
            $data['flat_cost'] = $this->config->get('flat_cost');
        }

        if (isset($this->request->post['flat_tax_class_id'])) {
            $data['flat_tax_class_id'] = $this->request->post['flat_tax_class_id'];
        } else {
            $data['flat_tax_class_id'] = $this->config->get('flat_tax_class_id');
        }

        $this->load->model('localisation/tax_class');

        $data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        if (isset($this->request->post['flat_geo_zone_id'])) {
            $data['flat_geo_zone_id'] = $this->request->post['flat_geo_zone_id'];
        } else {
            $data['flat_geo_zone_id'] = $this->config->get('flat_geo_zone_id');
        }

        $this->load->model('localisation/geo_zone');

        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['flat_status'])) {
            $data['flat_status'] = $this->request->post['flat_status'];
        } else {
            $data['flat_status'] = $this->config->get('flat_status');
        }

        if (isset($this->request->post['flat_sort_order'])) {
            $data['flat_sort_order'] = $this->request->post['flat_sort_order'];
        } else {
            $data['flat_sort_order'] = $this->config->get('flat_sort_order');
        }

        if (isset($this->request->post['flat_description'])) {
            $data['flat_description'] = $this->request->post['flat_description'];
        } else {
            $data['flat_description'] = $this->config->get('flat_description');
        }
        $data['languages'] = $this->model_localisation_language->getLanguages();

        //pr($data['module_description']);
        $data['token'] = $this->session->data['token'];


        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/shipping/flat', $data));
    }

    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/shipping/flat')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

}