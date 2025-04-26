<?php

namespace Modules\PkgWidgets\Services\WidgetServiceTraits;

trait WidgetLinkGenerator
{
    public function linkGenerator($widget, array $query)
    {
        if (!empty($query['link']) && !empty($query['link']['route_name'])) {

            $routeName = $query['link']['route_name'];
            $params = $query['link']['route_params'] ?? [];
            // fix user id params
            foreach ($params as $key => $value) {
                if ($value === '#apprenant_id') {
                    $params[$key] = $this->sessionState->get("apprenant_id");
                }
                if ($value === '#user_id') {
                    $params[$key] = $this->sessionState->get("user_id");
                }
                if ($value === '#formateur_id') {
                    $params[$key] = $this->sessionState->get("formateur_id");
                }
            }
            $widget->link = route($routeName, $params);
        }   
   
    }
}
