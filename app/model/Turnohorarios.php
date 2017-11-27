<?php





/**
 * Turnohorarios
 *
 * @Table(name="turnohorarios", indexes={@Index(name="fk_TurnoHorario_Registro1_idx", columns={"Turno"})})
 * @Entity
 */
class Turnohorarios
{
    /**
     * @var integer
     *
     * @Column(name="Id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="HoraAinicio", type="string", length=10, nullable=false)
     */
    private $horaainicio;

    /**
     * @var string
     *
     * @Column(name="HoraAfim", type="string", length=10, nullable=false)
     */
    private $horaafim;

    /**
     * @var string
     *
     * @Column(name="HoraBinicio", type="string", length=10, nullable=false)
     */
    private $horabinicio;

    /**
     * @var string
     *
     * @Column(name="HoraBfim", type="string", length=10, nullable=false)
     */
    private $horabfim;

    /**
     * @var \Registro
     *
     * @ManyToOne(targetEntity="Registro")
     * @JoinColumns({
     *   @JoinColumn(name="Turno", referencedColumnName="Id")
     * })
     */
    private $turno;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set horaainicio
     *
     * @param string $horaainicio
     *
     * @return Turnohorarios
     */
    public function setHoraainicio($horaainicio)
    {
        $this->horaainicio = $horaainicio;

        return $this;
    }

    /**
     * Get horaainicio
     *
     * @return string
     */
    public function getHoraainicio()
    {
        return $this->horaainicio;
    }

    /**
     * Set horaafim
     *
     * @param string $horaafim
     *
     * @return Turnohorarios
     */
    public function setHoraafim($horaafim)
    {
        $this->horaafim = $horaafim;

        return $this;
    }

    /**
     * Get horaafim
     *
     * @return string
     */
    public function getHoraafim()
    {
        return $this->horaafim;
    }

    /**
     * Set horabinicio
     *
     * @param string $horabinicio
     *
     * @return Turnohorarios
     */
    public function setHorabinicio($horabinicio)
    {
        $this->horabinicio = $horabinicio;

        return $this;
    }

    /**
     * Get horabinicio
     *
     * @return string
     */
    public function getHorabinicio()
    {
        return $this->horabinicio;
    }

    /**
     * Set horabfim
     *
     * @param string $horabfim
     *
     * @return Turnohorarios
     */
    public function setHorabfim($horabfim)
    {
        $this->horabfim = $horabfim;

        return $this;
    }

    /**
     * Get horabfim
     *
     * @return string
     */
    public function getHorabfim()
    {
        return $this->horabfim;
    }

    /**
     * Set turno
     *
     * @param \Registro $turno
     *
     * @return Turnohorarios
     */
    public function setTurno(\Registro $turno = null)
    {
        $this->turno = $turno;

        return $this;
    }

    /**
     * Get turno
     *
     * @return \Registro
     */
    public function getTurno()
    {
        return $this->turno;
    }
}

