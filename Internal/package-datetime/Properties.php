<?php namespace ZN\DateTime;
/**
 * ZN PHP Web Framework
 * 
 * "Simplicity is the ultimate sophistication." ~ Da Vinci
 * 
 * @package ZN
 * @license MIT [http://opensource.org/licenses/MIT]
 * @author  Ozan UYKUN [ozan@znframework.com]
 */

class Properties
{
    /**
     * Sets time format chars.
     * 
     * @var array
     */
    public static $setTimeFormatChars =
    [
        '{day-}|{shortDayName}|{shortDay}|{SD}'                     => '%a',
        '{day}|{dayName}|{day}|{D}'                                 => '%A',
        '{dayInWeek}|{weekDayNumber}|{weekDayNum}|{WDN}'            => '%w',
        '{dayInMonth}|{dayNumber0}|{dayNum0}|{DN0}'                 => '%d',
        '{dayInMonth-}|{dayNumber}|{dayNum}|{DN}'                   => '%e',
        '{dayInYear}|{yearDayNumber0}|{yearDayNum0}|{YDN0}'         => '%j',
        '{dayInYear-}|{yearDayNumber}|{yearDayNum}|{YDN}'           => 'auto',
        '{dayCountInMonth}|{totalDays}|{TD}'                        => 'auto',
        '{weekInYear}|{weekNumber}|{weekNum}|{WN}'                  => '%U',
        '{month-}|{shortMonthName}|{shortMonth}|{SM}'               => '%b',
        '{month}|{monthName}|{month}|{mon}|{M}'                     => '%B',
        '{monthInYear}|{monthNumber0}|{monNum0}|{MN0}'              => '%m',
        '{monthInYear-}|{monthNumber}|{monNum}|{MN}'                => 'auto',
        '{century}|{cen}'                                           => 'auto',
        '{century-}|{cen-}'                                         => '%C',
        '{year-}|{shortYear}|{SY}'                                  => '%y',
        '{year}|{Y}'                                                => '%Y',
        '{isLeapYear}|{ILY}'                                        => 'auto',
        '{hour}|{hour024}|{H024}'                                   => '%H',
        '{hour-}|{hour24}|{H24}'                                    => '%k',
        '{clock}|{hour012}|{H012}'                                  => '%I',
        '{clock-}|{hour12}|{H12}'                                   => '%l',
        '{minute}|{minute0}|{min}|{min0}'                           => '%M',
        '{second}|{second0}|{sec}|{sec0}'                           => '%S',
        '{am}|{AMPM}'                                               => '%p',
        '{am-}|{ampm}'                                              => '%P',   
        '{msecond}|{microSecond}|{micSec}|{MS}'                     => 'auto',
        '{iso}'                                                     => 'auto',
        '{rfc}'                                                     => 'auto',
        '{unix}'                                                    => 'auto'
    ];

    /**
     * Sets date format chars.
     * 
     * @var array
     */
    public static $setDateFormatChars =
    [
        '{day-}|{shortDayName}|{shortDay}|{SD}'                     => 'D',
        '{day}|{dayName}|{D}'                                       => 'l',
        '{dayInWeek}|{weekDayNumber}|{weekDayNum}|{WDN}'            => 'N',
        '{dayInMonth}|{dayNum0}|{dayNumber0}|{DN0}'                 => 'd',
        '{dayInMonth-}|{dayNum}|{dayNumber}|{DN}'                   => 'j',
        '{dayInYear}|{yearDayNumber0}|{yearDayNum0}|{YDN0}'         => 'auto',
        '{dayInYear-}|{yearDayNumber}|{yearDayNum}|{YDN}'           => 'z',
        '{dayCountInMonth}|{totalDays}|{TD}'                        => 't',  
        '{weekInYear}|{weekNumber}|{weekNum}|{WN}'                  => 'W',
        '{month-}|{shortMonthName}|{sortMonth}|{SM}'                => 'M',
        '{month}|{monthName}|{month}|{mon}|{M}'                     => 'F',
        '{monthInYear}|{monthNumber0}|{monNum0}|{MN0}'              => 'm',
        '{monthInYear-}|{monthNumber}|{monNum}|{MN}'                => 'n',
        '{century}|{cen}'                                           => 'auto',
        '{century-}|{cen-}'                                         => 'auto',
        '{year-}|{shortYear}|{SY}'                                  => 'y',
        '{year}|{Y}'                                                => 'Y',
        '{isLeapYear}|{ILY}'                                        => 'L',
        '{hour}|{hour024}|{H024}'                                   => 'H',
        '{hour-}|{hour24}|{H24}'                                    => 'G',
        '{clock}|{hour012}|{H012}'                                  => 'h',
        '{clock-}|{hour12}|{H12}'                                   => 'g',
        '{minute}|{minute0}|{min}|{min0}'                           => 'i',
        '{second}|{second0}|{sec}|{sec0}'                           => 's',
        '{am}|{AMPM}'                                               => 'A',
        '{am-}|{ampm}'                                              => 'a',
        '{msecond}|{microSecond}|{micSec}|{MS}'                     => 'u',
        '{iso}'                                                     => 'c',
        '{rfc}'                                                     => 'r',
        '{unix}'                                                    => 'U'
    ];
}
