<?php
/**
 * Calender functionalities
 */
class Time extends Query
{
    use View;

    public $days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
    public $months = array('Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre');
    
    private function getEvents($targets, $year){
        $events = $this->selectObject($targets, EVENTS_TABLE, YEAR_DATE_KEY, $year);

        /*
         *  Ce que je veux $r[TIMESTAMP][id] = title;
         */
        foreach ($events as $e) {
            $r[strtotime($e->date)][$e->event_id] = $e->title;
        }
        return $r;
    }

    /**
     * Get array calendar of a specified year
     * @param string $year The year of the calendar you want to generate
     * @return array Return Format $r[$y][$m][$d]
     */
    private function getAll($year){
        $r = array();
        $date = new DateTime($year.'-01-01');

        while($date->format('Y') <= $year){
            //$r = [ANNEE] [MOIS] [JOUR] = Jour de la semaine
            $y = $date->format('Y');
            $m = $date->format('n');
            $d = $date->format('j');
            $w = str_replace('0', '7', $date->format('w'));
            $r[$y][$m][$d] = $w;
            $date->add(new DateInterval('P1D'));
        }

        return $r;
    }

    public function myCalendar($targets, $currentYear)
    {
        $dates = $this->getAll($currentYear);
        $dates = current($dates);
        $events = $this->getEvents($targets, $currentYear);

        $this->displayCalendar($currentYear, $dates, new Time, $events);
    }
}
