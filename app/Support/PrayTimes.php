<?php

namespace App\Support;

class PrayTimes
{
    protected string $calcMethod = 'MWL';
    protected array $methods = [
        'MWL' => ['fajr' => 18, 'isha' => 17],
        'ISNA' => ['fajr' => 15, 'isha' => 15],
        'Egypt' => ['fajr' => 19.5, 'isha' => 17.5],
        'Makkah' => ['fajr' => 18.5, 'isha' => '90 min'],
        'Karachi' => ['fajr' => 18, 'isha' => 18],
        'Tehran' => ['fajr' => 17.7, 'isha' => 14, 'maghrib' => 4.5, 'midnight' => 'Jafari'],
        'Jafari' => ['fajr' => 16, 'isha' => 14, 'maghrib' => 4, 'midnight' => 'Jafari'],
    ];
    protected array $defaultParams = [
        'maghrib' => '0 min',
        'midnight' => 'Standard',
    ];
    protected array $setting = [
        'imsak' => '10 min',
        'dhuhr' => '0 min',
        'asr' => 'Standard',
        'highLats' => 'NightMiddle',
    ];
    protected array $offset = [];

    protected float $lat = 0.0;
    protected float $lng = 0.0;
    protected float $elv = 0.0;
    protected float $timeZone = 0.0;
    protected float $jDate = 0.0;

    public function __construct(?string $method = null)
    {
        foreach ($this->methods as $key => $params) {
            foreach ($this->defaultParams as $k => $v) {
                if (!array_key_exists($k, $params)) {
                    $this->methods[$key][$k] = $v;
                }
            }
        }
        if ($method && isset($this->methods[$method])) {
            $this->calcMethod = $method;
        }
        foreach ($this->methods[$this->calcMethod] as $id => $val) {
            $this->setting[$id] = $val;
        }
        foreach ($this->timeNames() as $n => $label) {
            $this->offset[$n] = 0;
        }
    }

    public function setMethod(string $method): void
    {
        if (isset($this->methods[$method])) {
            $this->adjust($this->methods[$method]);
            $this->calcMethod = $method;
        }
    }

    public function adjust(array $params): void
    {
        foreach ($params as $id => $val) {
            $this->setting[$id] = $val;
        }
    }

    public function tune(array $offsets): void
    {
        foreach ($offsets as $id => $val) {
            $this->offset[$id] = $val;
        }
    }

    public function getTimes(\DateTimeInterface $date, array $coords, float $timezone, bool $dst = false): array
    {
        $this->lat = (float)($coords[0] ?? 0);
        $this->lng = (float)($coords[1] ?? 0);
        $this->elv = (float)($coords[2] ?? 0);
        $this->timeZone = (float)$timezone + ($dst ? 1.0 : 0.0);
        $this->jDate = $this->julian((int)$date->format('Y'), (int)$date->format('n'), (int)$date->format('j')) - $this->lng / (15.0 * 24.0);

        $times = $this->computeTimes();
        return [
            'fajr' => $this->formatTime($times['fajr']),
            'dhuhr' => $this->formatTime($times['dhuhr']),
            'asr' => $this->formatTime($times['asr']),
            'maghrib' => $this->formatTime($times['maghrib']),
            'isha' => $this->formatTime($times['isha']),
        ];
    }

    protected function computeTimes(): array
    {
        $times = [
            'imsak' => 5,
            'fajr' => 5,
            'sunrise' => 6,
            'dhuhr' => 12,
            'asr' => 13,
            'sunset' => 18,
            'maghrib' => 18,
            'isha' => 18,
        ];
        $times = $this->computePrayerTimes($times);
        $times = $this->adjustTimes($times);
        $times = $this->tuneTimes($times);
        return $times;
    }

    protected function computePrayerTimes(array $times): array
    {
        $times = $this->dayPortion($times);
        $params = $this->setting;

        $imsak = $this->sunAngleTime($this->evalParam($params['imsak']), $times['imsak'], true);
        $fajr = $this->sunAngleTime($this->evalParam($params['fajr']), $times['fajr'], true);
        $sunrise = $this->sunAngleTime($this->riseSetAngle(), $times['sunrise'], true);
        $dhuhr = $this->midDay($times['dhuhr']);
        $asr = $this->asrTime($this->asrFactor($params['asr']), $times['asr']);
        $sunset = $this->sunAngleTime($this->riseSetAngle(), $times['sunset'], false);
        $maghrib = $this->sunAngleTime($this->evalParam($params['maghrib']), $times['maghrib'], false);
        $isha = $this->sunAngleTime($this->evalParam($params['isha']), $times['isha'], false);

        return compact('imsak', 'fajr', 'sunrise', 'dhuhr', 'asr', 'sunset', 'maghrib', 'isha');
    }

    protected function adjustTimes(array $times): array
    {
        $params = $this->setting;
        foreach ($times as $k => $v) {
            $times[$k] = $v + $this->timeZone - $this->lng / 15.0;
        }
        if ($params['highLats'] !== 'None') {
            $times = $this->adjustHighLats($times);
        }
        if ($this->isMin($params['imsak'])) {
            $times['imsak'] = $times['fajr'] - $this->evalParam($params['imsak']) / 60.0;
        }
        if ($this->isMin($params['maghrib'])) {
            $times['maghrib'] = $times['sunset'] + $this->evalParam($params['maghrib']) / 60.0;
        }
        if ($this->isMin($params['isha'])) {
            $times['isha'] = $times['maghrib'] + $this->evalParam($params['isha']) / 60.0;
        }
        $times['dhuhr'] += $this->evalParam($params['dhuhr']) / 60.0;
        return $times;
    }

    protected function adjustHighLats(array $times): array
    {
        $params = $this->setting;
        $nightTime = $this->timeDiff($times['sunset'], $times['sunrise']);
        $times['imsak'] = $this->adjustHLTime($times['imsak'], $times['sunrise'], $this->evalParam($params['imsak']), $nightTime, true);
        $times['fajr'] = $this->adjustHLTime($times['fajr'], $times['sunrise'], $this->evalParam($params['fajr']), $nightTime, true);
        $times['isha'] = $this->adjustHLTime($times['isha'], $times['sunset'], $this->evalParam($params['isha']), $nightTime, false);
        $times['maghrib'] = $this->adjustHLTime($times['maghrib'], $times['sunset'], $this->evalParam($params['maghrib']), $nightTime, false);
        return $times;
    }

    protected function adjustHLTime(float $time, float $base, float $angle, float $night, bool $ccw): float
    {
        $portion = $this->nightPortion($angle, $night);
        $timeDiff = $ccw ? $this->timeDiff($time, $base) : $this->timeDiff($base, $time);
        if (is_nan($time) || $timeDiff > $portion) {
            $time = $base + ($ccw ? -$portion : $portion);
        }
        return $time;
    }

    protected function nightPortion(float $angle, float $night): float
    {
        $method = $this->setting['highLats'];
        $portion = 0.5;
        if ($method === 'AngleBased') {
            $portion = $angle / 60.0;
        } elseif ($method === 'OneSeventh') {
            $portion = 1.0 / 7.0;
        }
        return $portion * $night;
    }

    protected function midDay(float $time): float
    {
        $eqt = $this->sunPosition($this->jDate + $time)['equation'];
        $noon = $this->fixHour(12.0 - $eqt);
        return $noon;
    }

    protected function sunAngleTime(float $angle, float $time, bool $ccw = false): float
    {
        $decl = $this->sunPosition($this->jDate + $time)['declination'];
        $noon = $this->midDay($time);
        $t = (1.0 / 15.0) * $this->arccos((- $this->sin($angle) - $this->sin($decl) * $this->sin($this->lat)) / ($this->cos($decl) * $this->cos($this->lat)));
        return $noon + ($ccw ? -$t : $t);
    }

    protected function asrTime(float $factor, float $time): float
    {
        $decl = $this->sunPosition($this->jDate + $time)['declination'];
        $angle = -$this->arccot($factor + $this->tan(abs($this->lat - $decl)));
        return $this->sunAngleTime($angle, $time, false);
    }

    protected function sunPosition(float $jd): array
    {
        $D = $jd - 2451545.0;
        $g = $this->fixAngle(357.529 + 0.98560028 * $D);
        $q = $this->fixAngle(280.459 + 0.98564736 * $D);
        $L = $this->fixAngle($q + 1.915 * $this->sin($g) + 0.020 * $this->sin(2 * $g));
        $R = 1.00014 - 0.01671 * $this->cos($g) - 0.00014 * $this->cos(2 * $g);
        $e = 23.439 - 0.00000036 * $D;
        $RA = $this->arctan2($this->cos($e) * $this->sin($L), $this->cos($L)) / 15.0;
        $eqt = $q / 15.0 - $this->fixHour($RA);
        $decl = $this->arcsin($this->sin($e) * $this->sin($L));
        return ['declination' => $decl, 'equation' => $eqt];
    }

    protected function julian(int $year, int $month, int $day): float
    {
        if ($month <= 2) {
            $year -= 1;
            $month += 12;
        }
        $A = (int)floor($year / 100);
        $B = 2 - $A + (int)floor($A / 4);
        $JD = (int)floor(365.25 * ($year + 4716)) + (int)floor(30.6001 * ($month + 1)) + $day + $B - 1524.5;
        return (float)$JD;
    }

    protected function riseSetAngle(): float
    {
        $angle = 0.0347 * sqrt($this->elv);
        return 0.833 + $angle;
    }

    protected function tuneTimes(array $times): array
    {
        foreach ($times as $i => $v) {
            $times[$i] = $v + ($this->offset[$i] ?? 0) / 60.0;
        }
        return $times;
    }

    protected function modifyFormats(array $times): array
    {
        foreach ($times as $i => $v) {
            $times[$i] = $this->formatTime($v);
        }
        return $times;
    }

    protected function dayPortion(array $times): array
    {
        foreach ($times as $i => $v) {
            $times[$i] = $v / 24.0;
        }
        return $times;
    }

    protected function timeDiff(float $time1, float $time2): float
    {
        return $this->fixHour($time2 - $time1);
    }

    protected function evalParam($str): float
    {
        $s = (string)$str;
        $m = preg_split('/[^0-9.+-]/', $s);
        return (float)($m[0] ?? 0);
    }

    protected function isMin($arg): bool
    {
        return str_contains((string)$arg, 'min');
    }

    protected function asrFactor($asrParam): float
    {
        $factor = ['Standard' => 1.0, 'Hanafi' => 2.0][$asrParam] ?? null;
        return $factor ?? $this->evalParam($asrParam);
    }

    protected function formatTime(float $time): string
    {
        $time = $this->fixHour($time + 0.5 / 60.0);
        $hours = (int)floor($time);
        $minutes = (int)floor(($time - $hours) * 60.0);
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    protected function timeNames(): array
    {
        return [
            'imsak' => 'Imsak',
            'fajr' => 'Fajr',
            'sunrise' => 'Sunrise',
            'dhuhr' => 'Dhuhr',
            'asr' => 'Asr',
            'sunset' => 'Sunset',
            'maghrib' => 'Maghrib',
            'isha' => 'Isha',
            'midnight' => 'Midnight',
        ];
    }

    protected function sin(float $d): float { return sin($this->dtr($d)); }
    protected function cos(float $d): float { return cos($this->dtr($d)); }
    protected function tan(float $d): float { return tan($this->dtr($d)); }
    protected function arcsin(float $d): float { return $this->rtd(asin($d)); }
    protected function arccos(float $d): float { return $this->rtd(acos($d)); }
    protected function arctan2(float $y, float $x): float { return $this->rtd(atan2($y, $x)); }
    protected function arccot(float $x): float { return $this->rtd(atan(1.0 / $x)); }
    protected function dtr(float $d): float { return ($d * M_PI) / 180.0; }
    protected function rtd(float $r): float { return ($r * 180.0) / M_PI; }
    protected function fixAngle(float $a): float { return $this->fix($a, 360.0); }
    protected function fixHour(float $a): float { return $this->fix($a, 24.0); }
    protected function fix(float $a, float $b): float
    {
        $a = $a - $b * floor($a / $b);
        return ($a < 0) ? $a + $b : $a;
    }
}

