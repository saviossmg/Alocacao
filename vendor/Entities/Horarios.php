<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Horarios
 *
 * @ORM\Table(name="horarios", indexes={@ORM\Index(name="fk_Horarios_registros1_idx", columns={"Turno"}), @ORM\Index(name="fk_Horarios_semestre1_idx", columns={"Semestre"})})
 * @ORM\Entity
 */
class Horarios
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Descricao", type="string", length=45, nullable=false)
     */
    private $descricao;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Inicio", type="time", nullable=false)
     */
    private $inicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Fim", type="time", nullable=false)
     */
    private $fim;

    /**
     * @var \Registro
     *
     * @ORM\ManyToOne(targetEntity="Registro")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Turno", referencedColumnName="Id")
     * })
     */
    private $turno;

    /**
     * @var \Vwsemestre
     *
     * @ORM\ManyToOne(targetEntity="Vwsemestre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Semestre", referencedColumnName="Id")
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
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Horarios
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get descricao
     *
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set inicio
     *
     * @param \DateTime $inicio
     *
     * @return Horarios
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fim
     *
     * @param \DateTime $fim
     *
     * @return Horarios
     */
    public function setFim($fim)
    {
        $this->fim = $fim;

        return $this;
    }

    /**
     * Get fim
     *
     * @return \DateTime
     */
    public function getFim()
    {
        return $this->fim;
    }

    /**
     * Set turno
     *
     * @param \Registro $turno
     *
     * @return Horarios
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
     * Set semestre
     *
     * @param \Vwsemestre $semestre
     *
     * @return Horarios
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

