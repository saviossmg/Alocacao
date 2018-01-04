<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Vwdisciplina
 *
 * @ORM\Table(name="vwdisciplina", indexes={@ORM\Index(name="fk_vwDisciplina_vwCurso1_idx", columns={"Curso"}), @ORM\Index(name="fk_vwDisciplina_vwDisciplina1_idx", columns={"PreRequesito"})})
 * @ORM\Entity
 */
class Vwdisciplina
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
     * @var integer
     *
     * @ORM\Column(name="Periodo", type="integer", nullable=false)
     */
    private $periodo;

    /**
     * @var string
     *
     * @ORM\Column(name="Descricao", type="string", length=45, nullable=false)
     */
    private $descricao;

    /**
     * @var integer
     *
     * @ORM\Column(name="CodCurricular", type="integer", nullable=false)
     */
    private $codcurricular;

    /**
     * @var \Vwcurso
     *
     * @ORM\ManyToOne(targetEntity="Vwcurso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Curso", referencedColumnName="Id")
     * })
     */
    private $curso;

    /**
     * @var \Vwdisciplina
     *
     * @ORM\ManyToOne(targetEntity="Vwdisciplina")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PreRequesito", referencedColumnName="Id")
     * })
     */
    private $prerequesito;


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
     * Set periodo
     *
     * @param integer $periodo
     *
     * @return Vwdisciplina
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return integer
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set descricao
     *
     * @param string $descricao
     *
     * @return Vwdisciplina
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
     * Set codcurricular
     *
     * @param integer $codcurricular
     *
     * @return Vwdisciplina
     */
    public function setCodcurricular($codcurricular)
    {
        $this->codcurricular = $codcurricular;

        return $this;
    }

    /**
     * Get codcurricular
     *
     * @return integer
     */
    public function getCodcurricular()
    {
        return $this->codcurricular;
    }

    /**
     * Set curso
     *
     * @param \Vwcurso $curso
     *
     * @return Vwdisciplina
     */
    public function setCurso(\Vwcurso $curso = null)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso
     *
     * @return \Vwcurso
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * Set prerequesito
     *
     * @param \Vwdisciplina $prerequesito
     *
     * @return Vwdisciplina
     */
    public function setPrerequesito(\Vwdisciplina $prerequesito = null)
    {
        $this->prerequesito = $prerequesito;

        return $this;
    }

    /**
     * Get prerequesito
     *
     * @return \Vwdisciplina
     */
    public function getPrerequesito()
    {
        return $this->prerequesito;
    }
}

