<?php
/**
 * Laboratorio
 *
 * @Table(name="laboratorio", indexes={@Index(name="fk_Laboratorio_Sala1_idx", columns={"Laboratorio"}), @Index(name="fk_Laboratorio_Registro1_idx", columns={"TipoUso"}), @Index(name="fk_Laboratorio_Registro2_idx", columns={"Turno"}), @Index(name="fk_Laboratorio_Registro3_idx", columns={"Dia"}), @Index(name="fk_Laboratorio_VwSemestre1_idx", columns={"Semestre"})})
 * @Entity
 */
class Laboratorio
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
     * @Column(name="Observacao", type="string", length=1000, nullable=true)
     */
    private $observacao;

    /**
     * @var \Registro
     *
     * @ManyToOne(targetEntity="Registro")
     * @JoinColumns({
     *   @JoinColumn(name="TipoUso", referencedColumnName="Id")
     * })
     */
    private $tipouso;

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
     * @var \Registro
     *
     * @ManyToOne(targetEntity="Registro")
     * @JoinColumns({
     *   @JoinColumn(name="Dia", referencedColumnName="Id")
     * })
     */
    private $dia;

    /**
     * @var \Sala
     *
     * @ManyToOne(targetEntity="Sala")
     * @JoinColumns({
     *   @JoinColumn(name="Laboratorio", referencedColumnName="Id")
     * })
     */
    private $laboratorio;

    /**
     * @var \Vwsemestre
     *
     * @ManyToOne(targetEntity="Vwsemestre")
     * @JoinColumns({
     *   @JoinColumn(name="Semestre", referencedColumnName="Id")
     * })
     */
    private $semestre;


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
     * Set observacao
     *
     * @param string $observacao
     *
     * @return Laboratorio
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;

        return $this;
    }

    /**
     * Get observacao
     *
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }

    /**
     * Set tipouso
     *
     * @param \Registro $tipouso
     *
     * @return Laboratorio
     */
    public function setTipouso(\Registro $tipouso = null)
    {
        $this->tipouso = $tipouso;

        return $this;
    }

    /**
     * Get tipouso
     *
     * @return \Registro
     */
    public function getTipouso()
    {
        return $this->tipouso;
    }

    /**
     * Set turno
     *
     * @param \Registro $turno
     *
     * @return Laboratorio
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

    /**
     * Set dia
     *
     * @param \Registro $dia
     *
     * @return Laboratorio
     */
    public function setDia(\Registro $dia = null)
    {
        $this->dia = $dia;

        return $this;
    }

    /**
     * Get dia
     *
     * @return \Registro
     */
    public function getDia()
    {
        return $this->dia;
    }

    /**
     * Set laboratorio
     *
     * @param \Sala $laboratorio
     *
     * @return Laboratorio
     */
    public function setLaboratorio(\Sala $laboratorio = null)
    {
        $this->laboratorio = $laboratorio;

        return $this;
    }

    /**
     * Get laboratorio
     *
     * @return \Sala
     */
    public function getLaboratorio()
    {
        return $this->laboratorio;
    }

    /**
     * Set semestre
     *
     * @param \Vwsemestre $semestre
     *
     * @return Laboratorio
     */
    public function setSemestre(\Vwsemestre $semestre = null)
    {
        $this->semestre = $semestre;

        return $this;
    }

    /**
     * Get semestre
     *
     * @return \Vwsemestre
     */
    public function getSemestre()
    {
        return $this->semestre;
    }
}

