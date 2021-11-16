<?php
/**
 * ---------------------------------------------------------------------
 * Formcreator is a plugin which allows creation of custom forms of
 * easy access.
 * ---------------------------------------------------------------------
 * LICENSE
 *
 * This file is part of Formcreator.
 *
 * Formcreator is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Formcreator is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Formcreator. If not, see <http://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 * @copyright Copyright © 2011 - 2021 Teclib'
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @link      https://github.com/pluginsGLPI/formcreator/
 * @link      https://pluginsglpi.github.io/formcreator/
 * @link      http://plugins.glpi-project.org/#/plugin/formcreator
 * ---------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access this file directly");
}

class PluginFormcreatorWizard {

   const MENU_CATALOG      = 1;
   const MENU_LAST_FORMS   = 2;
   const MENU_RESERVATIONS = 3;
   const MENU_FEEDS        = 4;
   const MENU_BOOKMARKS    = 5;
   const MENU_HELP         = 6;
   const MENU_FAQ          = 7;

   public static function showTicketSummary() {
      // show ticket summary
      echo "<span id='formcreator_servicecatalogue_ticket_summary'>";
      //$status_count = PluginFormcreatorIssue::getTicketSummary();

      $link = PluginFormcreatorIssue::getSearchURL();
      echo "<span class='status status_incoming'>
            <a href='".$link."?".
                     Toolbox::append_params(PluginFormcreatorIssue::getProcessingCriteria(), '&amp;')."'>
            <span class='status_number'><i class='fas fa-spinner fa-spin'></i></span>
            <label class='status_label'>".__('Processing')."</label>
            </a>
            </span>";

      echo "<span class='status status_waiting'>
            <a href='".$link."?".
                     Toolbox::append_params(PluginFormcreatorIssue::getWaitingCriteria(), '&amp;')."'>
            <span class='status_number'><i class='fas fa-spinner fa-spin'></i></span>
            <label class='status_label'>".__('Pending')."</label>
            </a>
            </span>";

      echo "<span class='status status_validate'>
            <a href='".$link."?".
                     Toolbox::append_params(PluginFormcreatorIssue::getValidateCriteria(), '&amp;')."'>
            <span class='status_number'><i class='fas fa-spinner fa-spin'></i></span>
            <label class='status_label'>".__('To validate', 'formcreator')."</label>
            </a>
            </span>";

      echo "<span class='status status_solved'>
            <a href='".$link."?".
                     Toolbox::append_params(PluginFormcreatorIssue::getSolvedCriteria(), '&amp;')."'>
            <span class='status_number'><i class='fas fa-spinner fa-spin'></i></span>
            <label class='status_label'>".__('Closed', 'formcreator')."</label>
            </a>
            </span>";

      echo '</span>'; // formcreator_servicecatalogue_ticket_summary
      echo Html::scriptBlock("$(function() {
         plugin_formcreator.getCounters();
      })");
   }

   protected static function findActiveMenuItem() {
      if (PluginFormcreatorEntityConfig::getUsedConfig('is_kb_separated', Session::getActiveEntity()) == PluginFormcreatorEntityConfig::CONFIG_KB_DISTINCT) {
         if (strpos($_SERVER['REQUEST_URI'], "formcreator/front/knowbaseitem.php") !== false
            || strpos($_SERVER['REQUEST_URI'], "formcreator/front/knowbaseitem.form.php") !== false) {
            return self::MENU_FAQ;
         }
      }
      if (strpos($_SERVER['REQUEST_URI'], "formcreator/front/wizard.php") !== false
          || strpos($_SERVER['REQUEST_URI'], "formcreator/front/formdisplay.php") !== false
          || strpos($_SERVER['REQUEST_URI'], "formcreator/front/knowbaseitem.form.php") !== false) {
         return self::MENU_CATALOG;
      }
      if (strpos($_SERVER['REQUEST_URI'], "formcreator/front/issue.php") !== false
          || strpos($_SERVER['REQUEST_URI'], "formcreator/front/issue.form.php") !== false) {
         return self::MENU_LAST_FORMS;
      }
      if (strpos($_SERVER['REQUEST_URI'], "formcreator/front/reservationitem.php") !== false) {
         return self::MENU_RESERVATIONS;
      }
      if (strpos($_SERVER['REQUEST_URI'], "formcreator/front/wizardfeeds.php") !== false) {
         return self::MENU_FEEDS;
      }
      return false;
   }
}
